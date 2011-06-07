<?php

/**
 * @package  sfBBCodeParserPlugin
 * @author   Stijn de Reede  <sjr@gmx.co.uk>
 * @author   COil
 * @since    1.0.0 - 29 avr 2009
 */

class sfBBCodeParser_Filter_Extended extends sfBBCodeParser_Filter
{

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
    $this->_definedTags = sfBBCodeParserPluginConfigHandler::getDefinedTagsForFilter('Extended');
  }
}