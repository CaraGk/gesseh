<?php

require_once dirname(__FILE__).'/../lib/admEtudiantGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/admEtudiantGeneratorHelper.class.php';

/**
 * admEtudiant actions.
 *
 * @package    gesseh
 * @subpackage admEtudiant
 * @author     Pierre-FrançoisPilouAngrand
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class admEtudiantActions extends autoAdmEtudiantActions
{
  /* Passe tous les étudiants dans la promo supérieure sauf la dernière promo */
  public function executeListAutoUpdatePromo()
  {
    $gesseh_promos = Doctrine::getTable('GessehPromo')
      ->createQuery('a')
      ->orderBy('a.active asc, a.ordre desc')
      ->execute();

    $promo_next = null;
    foreach($gesseh_promos as $gesseh_promo)
    {
      if($promo_next)
        Doctrine::getTable('GessehEtudiant')->changePromo($gesseh_promo->getId(), $promo_next);
      $promo_next = $gesseh_promo->getId();
    }

    $this->getUser()->setFlash('notice', sprintf('Tous les étudiants sont passés à la promotion supérieure.'));
    $this->redirect('admEtudiant/index');
  }

  /* Supprime les anciens étudiants lors d'une opération de maintenance */
  public function executeListDeleteAncien()
  {
    $this->redirect('admEtudiant/index');
  }

  /* Sors des étudiants des promotions actives */
  public function executeBatchHorsPromo(sfWebRequest $request)
  {
//    print_r($request);
    $etudiants = $request->getParameter('ids');

    foreach ($etudiants as $etudiant) {
      if ($valid)
        $valid .= ", ";
      if (Doctrine::getTable('GessehEtudiant')->changeHorsPromo($etudiant))
        $valid .= $etudiant->getSfGuardUser()->getLastName();
    }

    $this->getUser()->setFlash('notice', sprintf('%s sont sortis des promotions actives', $valid));
    $this->redirect('admEtudiant/index');
  }

  /* Formulaire d'import */
  public function executeListImportNew()
  {
    $this->form = new ImportForm();
    $this->setTemplate('import');
  }

  /* Importe des étudiants depuis un fichier */
  public function executeImportcreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->form = new ImportForm();

    $this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));
    if ($this->form->isValid())
    {
      $this->form->save('GessehEtudiant');
      $this->redirect('admEtudiant/index');
    }

    $this->setTemplate('import');
  }

  /* Envoie la génération de mot de passe aux étudiants sélectionnés */
  public function executeBatchSendpassword(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $ids = $request->getParameter('ids');

    $q = Doctrine_Query::create()
      ->from('GessehEtudiant a')
      ->leftJoin('a.sfGuardUser b')
      ->whereIn('a.id', $ids);

    foreach($q->execute() as $user) {
      $this->user = $user->getSfGuardUser();
//      $this->user->_deleteOldUserForgotPasswordRecords();

      $forgotPassword = new sfGuardForgotPassword();
      $forgotPassword->user_id = $this->user->id;
      $forgotPassword->unique_key = md5(rand() + time());
      $forgotPassword->expires_at = new Doctrine_Expression('NOW()');
      $forgotPassword->save();

      $message = Swift_Message::newInstance()
        ->setFrom(csSettings::get('email'))
        ->setTo($this->user->getEmailAddress())
        ->setSubject(csSettings::get('email_prefixe').' Génération de mot de passe pour '.$this->user->getFirstName().' '.$this->user->getLastName())
        ->setBody($this->getPartial('send_passwd', array('user' => $this->user, 'forgot_password' => $forgotPassword)))
        ->setContentType('text/html');

      $this->getMailer()->send($message);
    }

    $this->getUser()->setFlash('notice', 'Des e-mail ont été envoyés.');
    $this->redirect('admEtudiant/index');
  }
}
?>
