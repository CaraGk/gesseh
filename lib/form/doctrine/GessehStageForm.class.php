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
    if (csSettings::get('mod_simul') == false)
      unset($this['created_at'], $this['updated_at'], $this['is_active'], $this['form']);
    else
      unset($this['created_at'], $this['updated_at'], $this['is_active']);

    $query_terrain = Doctrine::getTable('GessehTerrain')->getListeTerrains('b.titre asc, a.titre asc');
    $this->widgetSchema['terrain_id']->setOption('query', $query_terrain);
    $this->validatorSchema['terrain_id']->setOption('query', $query_terrain);

    $query_etudiant = Doctrine::getTable('GessehEtudiant')->getActiveEtudiantsOrderByName();
    $this->widgetSchema['etudiant_id']->setOption('query', $query_etudiant);
    $this->validatorSchema['etudiant_id']->setOption('query', $query_etudiant);
  }
}
