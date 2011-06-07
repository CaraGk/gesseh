<?php

/**
 * This class is a modification of the PEAR::HTML_BBCodeParser.
 *  
 * The modification allows this plugin independent from standalone symfony 
 * plugin.  
 * It also adds the ability to add arbitrary html code before and after
 * all the tags defined in the filters with text replacment depending on tag
 * attributes (for now 1st parameter of quote = %author% parameter of 'before' 
 * html text.
 * 
 * @see PEAR::HTML_BBCodeParser
 * 
 * @author COil
 * @since  19 mar 08 
 */

class sfBBCodeParser
{
    /**
     * An array of tags parsed by the engine, should be overwritten by filters
     *
     * @access   private
     * @var      array
     */
    protected $_definedTags  = array();

    /**
     * A string containing the input
     *
     * @access   private
     * @var      string
     */
    protected $_text          = '';

    /**
     * A string containing the preparsed input
     *
     * @access   private
     * @var      string
     */
    protected $_preparsed     = '';

    /**
     * An array tags and texts build from the input text
     *
     * @access   private
     * @var      array
     */
    protected $_tagArray      = array();

    /**
     * A string containing the parsed version of the text
     *
     * @access   private
     * @var      string
     */
    protected $_parsed        = '';

    /**
     * An array of options, filled by an ini file or through the contructor
     *
     * @access   private
     * @var      array
     */
    protected $_options = array(
        'quotestyle'    => 'double',
        'quotewhat'     => 'all',
        'open'          => '[',
        'close'         => ']',
        'xmlclose'      => true,
        'filters'       => 'Email'
    );

    /**
     * An array of filters used for parsing
     *
     * @access   private
     * @var      array
     */
    protected $_filters       = array();

    /**
     * Name of filters
     */
    //protected static $_filterName = '';

    /**
     * Constructor, initialises the options and filters
     *
     * &PEAR::getStaticProperty(), overwriting them with (if present)
     * the argument to this method.
     * Then it sets the extra options to properly escape the tag
     * characters in preg_replace() etc. The set options are
     * then stored back with &PEAR::getStaticProperty(), so that the filter
     * classes can use them.
     * All the filters in the options are initialised and their defined tags
     * are copied into the private variable _definedTags.
     *
     * @param    array           options to use, can be left out
     * @return   none
     * @access   public
     * @author   Stijn de Reede  <sjr@gmx.co.uk>
     * @author   COil            <qrf_coil@yahoo.fr>
     */
    function sfBBCodeParser()
    {
        sfBBCodeParserPluginConfigHandler::load();
        $options = sfBBCodeParserPluginConfigHandler::getConfig();
        //mzTools::dump($options, '$options', 0);
        
        // set the already set options
        $this->_options =  $options;

        // add escape open and close chars to the options for preg escaping
        $preg_escape = '\^$.[]|()?*+{}';
        if ($this->_options['open'] != '' && strpos($preg_escape, $this->_options['open'])) {
            $this->_options['open_esc'] = "\\".$this->_options['open'];
        } else {
            $this->_options['open_esc'] = $this->_options['open'];
        }
        if ($this->_options['close'] != '' && strpos($preg_escape, $this->_options['close'])) {
            $this->_options['close_esc'] = "\\".$this->_options['close'];
        } else {
            $this->_options['close_esc'] = $this->_options['close'];
        }

        // return if this is a subclass
        if (is_subclass_of($this, 'sfBBCodeParser_Filter')) {
            return;
        }
        
        //mzTools::dump($this->_options, '$this->_options', 0);
        
        // Save config
        sfBBCodeParserPluginConfigHandler::setConfig($this->_options);
        
        //mzTools::dump(sfBBCodeParserPluginConfigHandler::getConfig(), 'sfBBCodeParserPluginConfigHandler::getConfig()', 1);

        // extract the definedTags from subclasses */
        $this->addFilters($this->_options['filters']);
    }

    /**
     * Option setter
     *
     * @param string option name
     * @param mixed  option value
     * @author Lorenzo Alberton <l.alberton@quipo.it>
     */
    function setOption($name, $value)
    {
        $this->_options[$name] = $value;
    }

    /**
     * Add a new filter
     *
     * @param string filter
     * @author Lorenzo Alberton <l.alberton@quipo.it>
     */
    function addFilter($filter)
    {
        $filter = ucfirst($filter);
        
        if (!array_key_exists($filter, $this->_filters)) {

            $class = 'sfBBCodeParser_Filter_'. $filter;

            if (!class_exists($class))
            {
                throw new sfException('Failed to load filter : '. $class. 
                  ' (You must create this filter and then clear your cache.)');
            }
            
            $this->_filters[$filter] = new $class;
            
            $this->_definedTags = array_merge(
                $this->_definedTags,
                $this->_filters[$filter]->_definedTags
            );
        }
    }

    /**
     * Remove an existing filter
     *
     * @param string $filter
     * @author Lorenzo Alberton <l.alberton@quipo.it>
     */
    function removeFilter($filter)
    {
        $filter = ucfirst(trim($filter));
        if (!empty($filter) && array_key_exists($filter, $this->_filters)) {
            unset($this->_filters[$filter]);
        }
        // also remove the related $this->_definedTags for this filter,
        // preserving the others
        $this->_definedTags = array();
        foreach (array_keys($this->_filters) as $filter) {
            $this->_definedTags = array_merge(
                $this->_definedTags,
                $this->_filters[$filter]->_definedTags
            );
        }
    }

    /**
     * Add new filters
     *
     * @param mixed (array or string)
     * @return boolean true if all ok, false if not.
     * @author Lorenzo Alberton <l.alberton@quipo.it>
     */
    function addFilters($filters)
    {
      if (is_string($filters)) 
      {
            //comma-separated list
            if (strpos($filters, ',') !== false) {
                $filters = explode(',', $filters);
            } else {
                $filters = array($filters);
            }
        }
        if (!is_array($filters)) {
            //invalid format
            return false;
        }
        foreach ($filters as $filter) {
            if (trim($filter)){
                $this->addFilter($filter);
            }
        }
        
        return true;
    }

    /**
     * Executes statements before the actual array building starts
     *
     * This method should be overwritten in a filter if you want to do
     * something before the parsing process starts. This can be useful to
     * allow certain short alternative tags which then can be converted into
     * proper tags with preg_replace() calls.
     * The main class walks through all the filters and and calls this
     * method. The filters should modify their private $_preparsed
     * variable, with input from $_text.
     *
     * @return   none
     * @access   private
     * @see      $_text
     * @author   Stijn de Reede  <sjr@gmx.co.uk>
     */
    function _preparse()
    {
        // default: assign _text to _preparsed, to be overwritten by filters
        $this->_preparsed = $this->_text;

        // return if this is a subclass
        if (is_subclass_of($this, 'sfBBCodeParser')) {
            return;
        }

        // walk through the filters and execute _preparse
        foreach ($this->_filters as $filter) {
            $filter->setText($this->_preparsed);
            $filter->_preparse();
            $this->_preparsed = $filter->getPreparsed();
        }
    }

    /**
     * Builds the tag array from the input string $_text
     *
     * An array consisting of tag and text elements is contructed from the
     * $_preparsed variable. The method uses _buildTag() to check if a tag is
     * valid and to build the actual tag to be added to the tag array.
     *
     * TODO: - rewrite whole method, as this one is old and probably slow
     *       - see if a recursive method would be better than an iterative one
     *
     * @return   none
     * @access   private
     * @see      _buildTag()
     * @see      $_text
     * @see      $_tagArray
     * @author   Stijn de Reede  <sjr@gmx.co.uk>
     */
    function _buildTagArray()
    {
        $this->_tagArray = array();
        $str = $this->_preparsed;
        $strPos = 0;
        $strLength = strlen($str);

        while (($strPos < $strLength)) {
            $tag = array();
            $openPos = strpos($str, $this->_options['open'], $strPos);
            if ($openPos === false) {
                $openPos = $strLength;
                $nextOpenPos = $strLength;
            }
            if ($openPos + 1 > $strLength) {
                $nextOpenPos = $strLength;
            } else {
                $nextOpenPos = strpos($str, $this->_options['open'], $openPos + 1);
                if ($nextOpenPos === false) {
                    $nextOpenPos = $strLength;
                }
            }
            $closePos = strpos($str, $this->_options['close'], $strPos);
            if ($closePos === false) {
                $closePos = $strLength + 1;
            }

            if ($openPos == $strPos) {
                if (($nextOpenPos < $closePos)) {
                    // new open tag before closing tag: treat as text
                    $newPos = $nextOpenPos;
                    $tag['text'] = substr($str, $strPos, $nextOpenPos - $strPos);
                    $tag['type'] = 0;
                } else {
                    // possible valid tag
                    $newPos = $closePos + 1;
                    $newTag = $this->_buildTag(substr($str, $strPos, $closePos - $strPos + 1));
                    if (($newTag !== false)) {
                        $tag = $newTag;
                    } else {
                        // no valid tag after all
                        $tag['text'] = substr($str, $strPos, $closePos - $strPos + 1);
                        $tag['type'] = 0;
                    }
                }
            } else {
                // just text
                $newPos = $openPos;
                $tag['text'] = substr($str, $strPos, $openPos - $strPos);
                $tag['type'] = 0;
            }

            // join 2 following text elements
            if ($tag['type'] === 0 && isset($prev) && $prev['type'] === 0) {
                $tag['text'] = $prev['text'].$tag['text'];
                array_pop($this->_tagArray);
            }

            $this->_tagArray[] = $tag;
            $prev = $tag;
            $strPos = $newPos;
        }
    }

    /**
     * Builds a tag from the input string
     *
     * This method builds a tag array based on the string it got as an
     * argument. If the tag is invalid, <false> is returned. The tag
     * attributes are extracted from the string and stored in the tag
     * array as an associative array.
     *
     * @param    string          string to build tag from
     * @return   array           tag in array format
     * @access   private
     * @see      _buildTagArray()
     * @author   Stijn de Reede  <sjr@gmx.co.uk>
     */
    function _buildTag($str)
    {
        $tag = array('text' => $str, 'attributes' => array());

        if (substr($str, 1, 1) == '/') {        // closing tag

            $tag['tag'] = strtolower(substr($str, 2, strlen($str) - 3));
            if (!in_array($tag['tag'], array_keys($this->_definedTags))) {
                return false;                   // nope, it's not valid
            } else {
                $tag['type'] = 2;
                return $tag;
            }
        } else {                                // opening tag

            $tag['type'] = 1;
            if (strpos($str, ' ') && (strpos($str, '=') === false)) {
                return false;                   // nope, it's not valid
            }

            // tnx to Onno for the regex
            // split the tag with arguments and all
            $oe = $this->_options['open_esc'];
            $ce = $this->_options['close_esc'];
            $tagArray = array();
            if (preg_match("!$oe([a-z0-9]+)[^$ce]*$ce!i", $str, $tagArray) == 0) {
                return false;
            }
            $tag['tag'] = strtolower($tagArray[1]);
            if (!in_array($tag['tag'], array_keys($this->_definedTags))) {
                return false;                   // nope, it's not valid
            }

            // tnx to Onno for the regex
            // validate the arguments
            $attributeArray = array();
            $regex = "![\s$oe]([a-z0-9]+)=(\"[^\s$ce]+\"|[^\s$ce]";
            if ($tag['tag'] != 'url') {
                $regex .= "[^=]";
            }
            $regex .= "+)(?=[\s$ce])!i";
            preg_match_all($regex, $str, $attributeArray, PREG_SET_ORDER);
            
            foreach ($attributeArray as $attribute) {
              
                // That's specific code for the quote tag in order to retrieve and store the quote author
                // We assume here that there is only one parameter and that's the author parameter
                if (isset($attribute[1]) && $attribute[1] == 'quote')
                {
                  if ($this->_options['quotestyle'] == 'single') $q = "'";
                  if ($this->_options['quotestyle'] == 'double') $q = '"';
                  $author = trim(str_ireplace($q, '', $attribute[2]));
                  $tag['author'] = $author;
                }
                
                $attNam = strtolower($attribute[1]);
                if (in_array($attNam, array_keys($this->_definedTags[$tag['tag']]['attributes']))) {
                    if ($attribute[2][0] == '"' && $attribute[2][strlen($attribute[2])-1] == '"') {
                        $tag['attributes'][$attNam] = substr($attribute[2], 1, -1);
                    } else {
                        $tag['attributes'][$attNam] = $attribute[2];
                    }
                }
            }
            return $tag;
        }
    }

    /**
     * Validates the tag array, regarding the allowed tags
     *
     * While looping through the tag array, two following text tags are
     * joined, and it is checked that the tag is allowed inside the
     * last opened tag.
     * By remembering what tags have been opened it is checked that
     * there is correct (xml compliant) nesting.
     * In the end all still opened tags are closed.
     *
     * @return   none
     * @access   private
     * @see      _isAllowed()
     * @see      $_tagArray
     * @author   Stijn de Reede  <sjr@gmx.co.uk>, Seth Price <seth@pricepages.org>
     */
    function _validateTagArray()
    {
        $newTagArray = array();
        $openTags = array();
        foreach ($this->_tagArray as $tag) {
            
            $prevTag = end($newTagArray);
            switch ($tag['type']) {
            case 0:
                if (($child = $this->_childNeeded(end($openTags), 'text')) &&
                    $child !== false &&
                    /*
                     * No idea what to do in this case: A child is needed, but
                     * no valid one is returned. We'll ignore it here and live
                     * with it until someone reports a valid bug.
                     */
                    $child !== true )
                {
                    if (trim($tag['text']) == '') {
                        //just an empty indentation or newline without value?
                        continue;
                    }
                    $newTagArray[] = $child;
                    $openTags[] = $child['tag'];
                }
                if ($prevTag['type'] === 0) {
                    $tag['text'] = $prevTag['text'].$tag['text'];
                    array_pop($newTagArray);
                }
                $newTagArray[] = $tag;
                break;

            case 1:
                if (!$this->_isAllowed(end($openTags), $tag['tag']) ||
                   ($parent = $this->_parentNeeded(end($openTags), $tag['tag'])) === true ||
                   ($child  = $this->_childNeeded(end($openTags),  $tag['tag'])) === true) {
                    $tag['type'] = 0;
                    if ($prevTag['type'] === 0) {
                        $tag['text'] = $prevTag['text'].$tag['text'];
                        array_pop($newTagArray);
                    }
                } else {
                    if ($parent) {
                        /*
                         * Avoid use of parent if we can help it. If we are
                         * trying to insert a new parent, but the current tag is
                         * the same as the previous tag, then assume that the
                         * previous tag structure is valid, and add this tag as
                         * a sibling. To add as a sibling, we need to close the
                         * current tag.
                         */
                        if ($tag['tag'] == end($openTags)){
                            $newTagArray[] = $this->_buildTag('[/'.$tag['tag'].']');
                            array_pop($openTags);
                        } else {
                            $newTagArray[] = $parent;
                            $openTags[] = $parent['tag'];
                        }
                    }
                    if ($child) {
                        $newTagArray[] = $child;
                        $openTags[] = $child['tag'];
                    }
                    $openTags[] = $tag['tag'];
                }
                $newTagArray[] = $tag;
                break;

            case 2:
                if (($tag['tag'] == end($openTags) || $this->_isAllowed(end($openTags), $tag['tag']))) {
                    if (in_array($tag['tag'], $openTags)) {
                        $tmpOpenTags = array();
                        while (end($openTags) != $tag['tag']) {
                            $newTagArray[] = $this->_buildTag('[/'.end($openTags).']');
                            $tmpOpenTags[] = end($openTags);
                            array_pop($openTags);
                        }
                        $newTagArray[] = $tag;
                        array_pop($openTags);
                        /* why is this here? it just seems to break things
                         * (nested lists where closing tags need to be
                         * generated)
                        while (end($tmpOpenTags)) {
                            $tmpTag = $this->_buildTag('['.end($tmpOpenTags).']');
                            $newTagArray[] = $tmpTag;
                            $openTags[] = $tmpTag['tag'];
                            array_pop($tmpOpenTags);
                        }*/
                    }
                } else {
                    $tag['type'] = 0;
                    if ($prevTag['type'] === 0) {
                        $tag['text'] = $prevTag['text'].$tag['text'];
                        array_pop($newTagArray);
                    }
                    $newTagArray[] = $tag;
                }
                break;
            }
        }
        while (end($openTags)) {
            $newTagArray[] = $this->_buildTag('[/'.end($openTags).']');
            array_pop($openTags);
        }
        $this->_tagArray = $newTagArray;
    }

    /**
     * Checks to see if a parent is needed
     *
     * Checks to see if the current $in tag has an appropriate parent. If it
     * does, then it returns false. If a parent is needed, then it returns the
     * first tag in the list to add to the stack.
     *
     * @param    array           tag that is on the outside
     * @param    array           tag that is on the inside
     * @return   boolean         false if not needed, tag if needed, true if out
     *                           of  our minds
     * @access   private
     * @see      _validateTagArray()
     * @author   Seth Price <seth@pricepages.org>
     */
    function _parentNeeded($out, $in)
    {
        if (!isset($this->_definedTags[$in]['parent']) ||
            ($this->_definedTags[$in]['parent'] == 'all')
        ) {
            return false;
        }

        $ar = explode('^', $this->_definedTags[$in]['parent']);
        $tags = explode(',', $ar[1]);
        if ($ar[0] == 'none'){
            if ($out && in_array($out, $tags)) {
                return false;
            }
            //Create a tag from the first one on the list
            return $this->_buildTag('['.$tags[0].']');
        }
        if ($ar[0] == 'all' && $out && !in_array($out, $tags)) {
            return false;
        }
        // Tag is needed, we don't know which one. We could make something up,
        // but it would be so random, I think that it would be worthless.
        return true;
    }

    /**
     * Checks to see if a child is needed
     *
     * Checks to see if the current $out tag has an appropriate child. If it
     * does, then it returns false. If a child is needed, then it returns the
     * first tag in the list to add to the stack.
     *
     * @param    array           tag that is on the outside
     * @param    array           tag that is on the inside
     * @return   boolean         false if not needed, tag if needed, true if out
     *                           of our minds
     * @access   private
     * @see      _validateTagArray()
     * @author   Seth Price <seth@pricepages.org>
     */
    function _childNeeded($out, $in)
    {
        if (!isset($this->_definedTags[$out]['child']) ||
           ($this->_definedTags[$out]['child'] == 'all')
        ) {
            return false;
        }

        $ar = explode('^', $this->_definedTags[$out]['child']);
        $tags = explode(',', $ar[1]);
        if ($ar[0] == 'none'){
            if ($in && in_array($in, $tags)) {
                return false;
            }
            //Create a tag from the first one on the list
            return $this->_buildTag('['.$tags[0].']');
        }
        if ($ar[0] == 'all' && $in && !in_array($in, $tags)) {
            return false;
        }
        // Tag is needed, we don't know which one. We could make something up,
        // but it would be so random, I think that it would be worthless.
        return true;
    }

    /**
     * Checks to see if a tag is allowed inside another tag
     *
     * The allowed tags are extracted from the private _definedTags array.
     *
     * @param    array           tag that is on the outside
     * @param    array           tag that is on the inside
     * @return   boolean         return true if the tag is allowed, false
     *                           otherwise
     * @access   private
     * @see      _validateTagArray()
     * @author   Stijn de Reede  <sjr@gmx.co.uk>
     */
    function _isAllowed($out, $in)
    {
        if (!$out || ($this->_definedTags[$out]['allowed'] == 'all')) {
            return true;
        }
        if ($this->_definedTags[$out]['allowed'] == 'none') {
            return false;
        }

        $ar = explode('^', $this->_definedTags[$out]['allowed']);
        $tags = explode(',', $ar[1]);
        if ($ar[0] == 'none' && in_array($in, $tags)) {
            return true;
        }
        if ($ar[0] == 'all'  && in_array($in, $tags)) {
            return false;
        }
        return false;
    }

    /**
     * Builds a parsed string based on the tag array
     *
     * The correct html and attribute values are extracted from the private
     * _definedTags array.
     *
     * @return   none
     * @access   private
     * @see      $_tagArray
     * @see      $_parsed
     * @author   Stijn de Reede  <sjr@gmx.co.uk>
     */
    function _buildParsedString()
    {
        $this->_parsed = '';
        foreach ($this->_tagArray as $tag) {
            switch ($tag['type']) {

            // just text
            case 0:
                $this->_parsed .= $tag['text'];
                break;

            // opening tag
            case 1:

                $attributes = $this->_definedTags[$tag['tag']]['attributes'];

                // Allow to put arbitrary code before the tag
                if (isset($this->_definedTags[$tag['tag']]['before'])) 
                {
                  $before_text = $this->_definedTags[$tag['tag']]['before'];
                  
                  // Specific code for quote tag
                  if (isset($tag['author']))
                  {
                    $before_text = str_ireplace('%author%', $tag['author'], $before_text);
                  }
                  $this->_parsed .= $before_text;
                }
                
                $this->_parsed .= '<'.$this->_definedTags[$tag['tag']]['htmlopen'];

                // added for sfPlugin
                $q = '';
                foreach ($tag['attributes'] as $a => $v) {
                    //prevent XSS attacks. IMHO this is not enough, though...
                    //@see http://pear.php.net/bugs/bug.php?id=5609
                    $v = preg_replace('#(script|about|applet|activex|chrome):#is', "\\1&#058;", $v);
                    $v = htmlspecialchars($v);
                    $v = str_replace('&amp;amp;', '&amp;', $v);

                    if (($this->_options['quotewhat'] == 'nothing') ||
                        (($this->_options['quotewhat'] == 'strings') && is_numeric($v))
                    ) {
                        $this->_parsed .= ' '.sprintf($this->_definedTags[$tag['tag']]['attributes'][$a], $v, '');
                    } else {
                        // Where does come from this $q var ???
                        $this->_parsed .= ' '.sprintf($this->_definedTags[$tag['tag']]['attributes'][$a], $v, $q);
                    }
                }
                if ($this->_definedTags[$tag['tag']]['htmlclose'] == '' && $this->_options['xmlclose']) {
                    $this->_parsed .= ' /';
                }
                $this->_parsed .= '>';

                break;

            // closing tag
            case 2:
                if ($this->_definedTags[$tag['tag']]['htmlclose'] != '') {
                    $this->_parsed .= '</'.$this->_definedTags[$tag['tag']]['htmlclose'].'>';
                }

                // Allow to put arbitrary code after the tag
                if (isset($this->_definedTags[$tag['tag']]['after']) != '') {
                    $this->_parsed .= $this->_definedTags[$tag['tag']]['after'];
                }
                
                break;
            }
        }
    }

    /**
     * Sets text in the object to be parsed
     *
     * @param    string          the text to set in the object
     * @return   none
     * @access   public
     * @see      getText()
     * @see      $_text
     * @author   Stijn de Reede  <sjr@gmx.co.uk>
     */
    function setText($str)
    {
        $this->_text = $str;
    }

    /**
     * Gets the unparsed text from the object
     *
     * @return   string          the text set in the object
     * @access   public
     * @see      setText()
     * @see      $_text
     * @author   Stijn de Reede  <sjr@gmx.co.uk>
     */
    function getText()
    {
        return $this->_text;
    }

    /**
     * Gets the preparsed text from the object
     *
     * @return   string          the text set in the object
     * @access   public
     * @see      _preparse()
     * @see      $_preparsed
     * @author   Stijn de Reede  <sjr@gmx.co.uk>
     */
    function getPreparsed()
    {
        return $this->_preparsed;
    }

    /**
     * Gets the parsed text from the object
     *
     * @return   string          the parsed text set in the object
     * @access   public
     * @see      parse()
     * @see      $_parsed
     * @author   Stijn de Reede  <sjr@gmx.co.uk>
     */
    function getParsed()
    {
        return $this->_parsed;
    }

    /**
     * Parses the text set in the object
     *
     * @return   none
     * @access   public
     * @see      _preparse()
     * @see      _buildTagArray()
     * @see      _validateTagArray()
     * @see      _buildParsedString()
     * @author   Stijn de Reede  <sjr@gmx.co.uk>
     */
    function parse()
    {
        $this->_preparse();
        $this->_buildTagArray();
        $this->_validateTagArray();
        $this->_buildParsedString();
    }

    /**
     * Quick method to do setText(), parse() and getParsed at once
     *
     * @return   none
     * @access   public
     * @see      parse()
     * @see      $_text
     * @author   Stijn de Reede  <sjr@gmx.co.uk>
     */
    function qparse($str)
    {
        $this->_text = $str;
        $this->parse();
        return $this->_parsed;
    }

    /**
     * Quick static method to do setText(), parse() and getParsed at once
     *
     * @return   none
     * @access   public
     * @see      parse()
     * @see      $_text
     * @author   Stijn de Reede  <sjr@gmx.co.uk>
     */
    function staticQparse($str)
    {
        $p = new sfBBCodeParser();
        $str = $p->qparse($str);
        unset($p);
        return $str;
    }
}