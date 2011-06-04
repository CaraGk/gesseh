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
     unset($this['username'], $this['is_active'], $this['is_super_admin'], $this['groups_list'], $this['permissions_list']);
   }
 }

?>
