<?php

/**
 * @package  sfBBCodeParserPlugin
 * @author   Stijn de Reede  <sjr@gmx.co.uk>
 * @author   COil
 * @since    1.0.0 - 29 avr 09
 */

class sfBBCodeParser_Filter_Links extends sfBBCodeParser_Filter
{

  /**
   * List of allowed schemes
   *
   * @access  private
   * @var     array
   */
  protected $_allowedSchemes = array('http', 'https', 'ftp');

  /**
   * Default scheme
   *
   * @access  private
   * @var     string
   */
  protected $_defaultScheme = 'http';
  
  /**
   * New constructor to retrieve the tags for the filter from 
   * the sf configuation.
   * 
   * @author COil
   * @since  19 mar 08
   */
  public function __construct($options = array())
  {
    // Standart constructor
    parent::__construct($options);

    // Now retrieves the attributes from the config file
    $this->_definedTags = sfBBCodeParserPluginConfigHandler::getDefinedTagsForFilter('Links');
  }    

  /**
   * Executes statements before the actual array building starts
   *
   * This method should be overwritten in a filter if you want to do
   * something before the parsing process starts. This can be useful to
   * allow certain short alternative tags which then can be converted into
   * proper tags with preg_replace() calls.
   * The main class walks through all the filters and and calls this
   * method if it exists. The filters should modify their private $_text
   * variable.
   *
   * @return   none
   * @access   private
   * @see      $_text
   * @author   Stijn de Reede <sjr@gmx.co.uk>
   * @author   Seth Price <seth@pricepages.org>
   */
  function _preparse()
  {
    $options = sfBBCodeParserPluginConfigHandler::getConfig();
    $o = $options['open'];
    $c = $options['close'];
    $oe = $options['open_esc'];
    $ce = $options['close_esc'];

    $schemes = implode('|', $this->_allowedSchemes);

    $pattern = array(   "/(?<![\"'=".$ce."\/])(".$oe."[^".$ce."]*".$ce.")?(((".$schemes."):\/\/|www)[@-a-z0-9.]+\.[a-z]{2,4}[^\s()\[\]]*)/i",
                        "!".$oe."url(".$ce."|\s.*".$ce.")(.*)".$oe."/url".$ce."!iU",
                        "!".$oe."url=((([a-z]*:(//)?)|www)[@-a-z0-9.]+)([^\s\[\]]*)".$ce."(.*)".$oe."/url".$ce."!i");

    $pp = preg_replace_callback($pattern[0], array($this, 'smarterPPLinkExpand'), $this->_text);
    $pp = preg_replace($pattern[1], $o."url=\$2\$1\$2".$o."/url".$c, $pp);
    $this->_preparsed = preg_replace_callback($pattern[2], array($this, 'smarterPPLink'), $pp);
  }

  /**
   * Intelligently expand a URL into a link
   *
   * @return  string
   * @access  private
   * @author  Seth Price <seth@pricepages.org>
   * @author  Lorenzo Alberton <l.alberton@quipo.it>
   */
  function smarterPPLinkExpand($matches)
  {
    $options = sfBBCodeParserPluginConfigHandler::getConfig();
    $o = $options['open'];
    $c = $options['close'];

    //If we have an intro tag that is [url], then skip this match
    if ($matches[1] == $o.'url'.$c) {
        return $matches[0];
    }

    $punctuation = '.,;:'; // Links can't end with these chars
    $trailing = '';
    // Knock off ending punctuation
    $last = substr($matches[2], -1);
    while (strpos($punctuation, $last) !== false) {
        // Last character is punctuation - remove it from the url
        $trailing = $last.$trailing;
        $matches[2] = substr($matches[2], 0, -1);
        $last = substr($matches[2], -1);
    }

    $off = strpos($matches[2], ':');

    //Is a ":" (therefore a scheme) defined?
    if ($off === false) {
        /*
         * Create a link with the default scheme of http. Notice that the
         * text that is viewable to the user is unchanged, but the link
         * itself contains the "http://".
         */
        return $matches[1].$o.'url='.$this->_defaultScheme.'://'.$matches[2].$c.$matches[2].$o.'/url'.$c.$trailing;
    }

    $scheme = substr($matches[2], 0, $off);

    /*
     * If protocol is in the approved list than allow it. Note that this
     * check isn't really needed, but the created link will just be deleted
     * later in smarterPPLink() if we create it now and it isn't on the
     * scheme list.
     */
    if (in_array($scheme, $this->_allowedSchemes)) {
        return $matches[1].$o.'url'.$c.$matches[2].$o.'/url'.$c.$trailing;
    }
    
    return $matches[0];
  }

  /**
   * Finish preparsing URL to clean it up
   *
   * @return  string
   * @access  private
   * @author  Seth Price <seth@pricepages.org>
   */
  function smarterPPLink($matches)
  {
    $options = sfBBCodeParserPluginConfigHandler::getConfig();
    $o = $options['open'];
    $c = $options['close'];

    $urlServ = $matches[1];
    $path = $matches[5];

    $off = strpos($urlServ, ':');

    if ($off === false) {
        //Default to http
        $urlServ = $this->_defaultScheme.'://'.$urlServ;
        $off = strpos($urlServ, ':');
    }

    //Add trailing slash if missing (to create a valid URL)
    if (!$path) {
        $path = '/';
    }

    $protocol = substr($urlServ, 0, $off);

    if (in_array($protocol, $this->_allowedSchemes)) {
        //If protocol is in the approved list than allow it
        return $o.'url='.$urlServ.$path.$c.$matches[6].$o.'/url'.$c;
    }
    
    //Else remove url tag
    return $matches[6];
  }
}