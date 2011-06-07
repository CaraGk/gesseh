<?php

/**
 * @package  sfBBCodeParserPlugin
 * @author   Stijn de Reede  <sjr@gmx.co.uk>
 * @author   COil
 * @since    1.0.0 - 29 avr 09
 */

class sfBBCodeParser_Filter_Email extends sfBBCodeParser_Filter
{

  /**
   * New constructor to retrieve the tags for the filter from 
   * the sf configuation.
   * 
   * @author COil
   * @since  29 avr 09
   */
  public function __construct($options = array())
  {
    // Standart constructor
    parent::__construct($options);

    // Now retrieves the attributes from the config file
    $this->_definedTags = sfBBCodeParserPluginConfigHandler::getDefinedTagsForFilter('Email');
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
   * @author   Stijn de Reede  <sjr@gmx.co.uk>
   * @author   COil
   * 
   * @internal Modified for sf plugin.
   */
  function _preparse()
  {
    $options = sfBBCodeParserPluginConfigHandler::getConfig();

    $o = $options['open'];
    $c = $options['close'];
    $oe = $options['open_esc'];
    $ce = $options['close_esc'];
    $pattern = array("!(^|\s)([-a-z0-9_.]+@[-a-z0-9.]+\.[a-z]{2,4})!i",
                     "!".$oe."email(".$ce."|\s.*".$ce.")(.*)".$oe."/email".$ce."!Ui");
    $replace = array("\\1".$o."email=\\2".$c."\\2".$o."/email".$c,
                     $o."email=\\2\\1\\2".$o."/email".$c);
    $this->_preparsed = preg_replace($pattern, $replace, $this->_text);
  }
}