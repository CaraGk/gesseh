<?php

/**
 * sfBBCodeParser actions.
 *
 * @package    sfBBCodeParser
 * @author     COil
 * @since      V1.0.0 - 29 avr 2009
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */

class sfBBCodeParserActions extends sfActions
{

 /**
  * Executes demo action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->bb_parser = new sfBBCodeParser();
  }
}