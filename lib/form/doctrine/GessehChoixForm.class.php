<?php

/**
 * GessehChoix form.
 *
 * @package    gesseh
 * @subpackage form
 * @author     Pierre-François Pilou Angrand <tmp@angrand.fr>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class GessehChoixForm extends BaseGessehChoixForm
{
  public function configure()
  {
    $this->useFields(array('id', 'poste'));

    $query_poste = Doctrine::getTable('GessehTerrain')->getListeTerrains('b.nom asc, a.titre asc');
    $this->widgetSchema['poste']->setOption('query', $query_poste);
    $this->validatorSchema['poste']->setOption('query', $query_poste);
  }
}
