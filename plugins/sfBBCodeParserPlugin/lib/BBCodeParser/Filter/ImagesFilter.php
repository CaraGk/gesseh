<?php

/**
 * @package  sfBBCodeParserPlugin
 * @author   Stijn de Reede  <sjr@gmx.co.uk>
 * @author   COil
 * @since    1.0.0 - 29 avr 09
 */

class sfBBCodeParser_Filter_Images extends sfBBCodeParser_Filter
{

  /**
   * New constructor to retrieve the tags for the filter from 
   * the sf configuation.
   * 
   * @author COil
   * @since  20 mar 08
   */
  public function __construct($options = array())
  {
    // Standart constructor
    parent::__construct($options);

    // Now retrieves the attributes from the config file
    $this->_definedTags = sfBBCodeParserPluginConfigHandler::getDefinedTagsForFilter('Images');
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
  * Modified for symfony plugin version.
  *
  * @return   none
  * @access   private
  * @see      $_text
  * @author   Stijn de Reede  <sjr@gmx.co.uk>
  * @author   COil
  */
  function _preparse()
  {
    $options = sfBBCodeParserPluginConfigHandler::getConfig();

    $o  = $options['open'];
    $c  = $options['close'];
    $oe = $options['open_esc'];
    $ce = $options['close_esc'];
    $this->_preparsed = preg_replace(
  		"!".$oe."img(\s?.*)".$ce."(.*)".$oe."/img".$ce."!Ui",
  		$o."img=\"\$2\"\$1".$c.$o."/img".$c,
  		$this->_text
    );
  }
}