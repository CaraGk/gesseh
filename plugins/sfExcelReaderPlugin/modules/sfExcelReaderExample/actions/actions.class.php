<?php

/**
 * sfExcelReaderExample actions.
 *
 * @package     sfExcelReaderPlugin
 * @author      Tomasz Ducin <tomasz.ducin@gmail.com>
 */

class sfExcelReaderExampleActions extends sfActions
{
  /**
   * Demo action. Interprets and displays an example xls file.
   *
   * @param sfRequest $request A request object
   */
  public function executeIndex(sfWebRequest $request)
  {
    error_reporting(E_ALL ^ E_NOTICE);
    $this->data = new sfExcelReader(sfConfig::get('sf_root_dir').'/plugins/sfExcelReaderPlugin/data/example.xls');

    // adding default excel reader stylesheet
    $this->getResponse()->addStylesheet('/sfExcelReaderPlugin/css/excel_reader');
  }
}
