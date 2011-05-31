<?php

/**
 * sfGuardUser form.
 *
 * @package    gesseh
 * @subpackage form
 * @author     Pierre-FrançoisPilouAngrand
 * @version    SVN: $Id: sfDoctrinePluginFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class sfGuardUserForm extends PluginsfGuardUserForm
{
  public function configure()
  {
    unset($this['algorithm'], $this['salt'], $this['is_active'], $this['is_super_admin'], $this['last_login'], $this['created_at'], $this['updated_at'], $this['username'], $this['password'], $this['groups_list'], $this['permissions_list']);
  }
}
