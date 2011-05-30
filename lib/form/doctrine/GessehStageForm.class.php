<?php

/**
 * GessehStage form.
 *
 * @package    gesseh
 * @subpackage form
 * @author     Pierre-FrançoisPilouAngrand
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class GessehStageForm extends BaseGessehStageForm
{
  public function configure()
  {
    unset($this['created_at'], $this['updated_at'], $this['is_active']);
  }
}
