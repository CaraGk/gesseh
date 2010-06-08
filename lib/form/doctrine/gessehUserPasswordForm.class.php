<?php

/**
 * GessehEtudiant form.
 *
 * @package    gesseh
 * @subpackage form
 * @author     Pierre-François "Pilou" Angrand
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
 
 class gessehUserPasswordForm extends BasesfGuardUserAdminForm
 {
   public function configure()
   {
     $this->useFields(array('password', 'password_again'));
     $this->disableLocalCSRFProtection();
   }
 }

?>
