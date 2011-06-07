<?php

/**
 * @package  sfBBCodeParserPlugin
 * @author   Stijn de Reede  <sjr@gmx.co.uk>
 * @author   COil <qrf_coil@yahoo.fr>
 * @since    1.0.0 - 29 avr 2009
 */

class sfBBCodeParser_Filter_Basic extends sfBBCodeParser_Filter
{
  /**
   * New constructor to retrieve the tags for the filter from 
   * the sf configuation.
   * 
   * @author COil
   * @since  V1.0.0 - 19 avr 09
   */
  public function __construct($options = array())
  {
    // Standart constructor
    parent::__construct($options);

    // Now retrives the attributes from the config file
    $this->_definedTags = sfBBCodeParserPluginConfigHandler::getDefinedTagsForFilter('Basic');  
  }
}