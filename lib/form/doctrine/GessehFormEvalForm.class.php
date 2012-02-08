<?php

/**
 * GessehFormEval form.
 *
 * @package    gesseh
 * @subpackage form
 * @author     Pierre-François 'Pilou' Angrand <tmp@angrand.fr>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class GessehFormEvalForm extends BaseGessehFormEvalForm
{
  protected $scheduledForDeletion = array();

  public function configure()
  {
    $this->embedRelation('GessehCritere');
//    $nouveau = new GessehCritereForm();
//    $this->embedForm('Nouvel item', $nouveau);
  }

  protected function doBind(array $values)
  {
    if (isset($values['GessehCritere'])) {
      foreach ($values['GessehCritere'] as $i => $criteriaValues) {
        if (isset($criteriaValues['delete']) && $criteriaValues['id']) {
          $this->scheduledForDeletion[$i] = $criteriaValues['id'];
        }
      }
    }

    parent::doBind($values);
  }

  protected function doUpdateObject($values)
  {
    if (count($this->scheduledForDeletion)) {
      foreach ($this->scheduledForDeletion as $index => $id) {
        unset($values['GessehCritere'][$index]);
        unset($this->object['GessehCritere'][$index]);
        Doctrine::getTable('GessehCritere')->findOneById($id)->delete();
      }
    }

    $this->getObject()->fromArray($values);
  }

  public function saveEmbeddedForms($con = null, $forms = null)
  {
    if (null === $con)
      $con = $this->getConnection();

    if (null === $forms)
      $forms = $this->embeddedForms;

    foreach ($forms as $form) {
      if ($form instanceof sfFormObject) {
        if (!in_array($form->getObject()->getId(), $this->scheduledForDeletion)) {
          $form->saveEmbeddedForms($con);
          $form->getObject()->save($con);
        }
      }else{
        $this->saveEmbeddedForms($con, $form->getEmbeddedForms());
      }
    }
  }

}
