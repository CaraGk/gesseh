<?php

/**
 * @package  sfBBCodeParserPlugin
 * @author   Stijn de Reede  <sjr@gmx.co.uk>
 * @author   COil
 * @since    1.0.0 - 28 avr 09
 */

class sfBBCodeParser_Filter_Lists extends sfBBCodeParser_Filter
{

  /**
   * New constructor to retrieve the tags for the filter from 
   * the sf configuation.
   * 
   * @author COil
   * @since  28 avr 09
   */
  public function __construct($options = array())
  {
    // Standart constructor
    parent::__construct($options);

    // Now retrieves the attributes from the config file
    $this->_definedTags = sfBBCodeParserPluginConfigHandler::getDefinedTagsForFilter('Lists');
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
   * Modified for symfony plugin.
   *
   * @return   none
   * @access   private
   * @see      $_text
   * @author   Stijn de Reede <sjr@gmx.co.uk>, Seth Price <seth@pricepages.org>
   * @author   COil
   */
  function _preparse()
  {
    $options = sfBBCodeParserPluginConfigHandler::getConfig();
    
    $o = $options['open'];
    $c = $options['close'];
    $oe = $options['open_esc'];
    $ce = $options['close_esc'];
    
    $pattern = array(   "!".$oe."\*".$ce."!",
                        "!".$oe."(u?)list=(?-i:A)(\s*[^".$ce."]*)".$ce."!i",
                        "!".$oe."(u?)list=(?-i:a)(\s*[^".$ce."]*)".$ce."!i",
                        "!".$oe."(u?)list=(?-i:I)(\s*[^".$ce."]*)".$ce."!i",
                        "!".$oe."(u?)list=(?-i:i)(\s*[^".$ce."]*)".$ce."!i",
                        "!".$oe."(u?)list=(?-i:1)(\s*[^".$ce."]*)".$ce."!i",
                        "!".$oe."(u?)list([^".$ce."]*)".$ce."!i");
    
    $replace = array(   $o."li".$c,
                        $o."\$1list=upper-alpha\$2".$c,
                        $o."\$1list=lower-alpha\$2".$c,
                        $o."\$1list=upper-roman\$2".$c,
                        $o."\$1list=lower-roman\$2".$c,
                        $o."\$1list=decimal\$2".$c,
                        $o."\$1list\$2".$c );
    
    $this->_preparsed = preg_replace($pattern, $replace, $this->_text);
  }
}