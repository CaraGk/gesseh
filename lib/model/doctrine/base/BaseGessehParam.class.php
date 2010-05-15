<?php

/**
 * BaseGessehParam
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $titre
 * @property string $valeur
 * 
 * @method string      getTitre()  Returns the current record's "titre" value
 * @method string      getValeur() Returns the current record's "valeur" value
 * @method GessehParam setTitre()  Sets the current record's "titre" value
 * @method GessehParam setValeur() Sets the current record's "valeur" value
 * 
 * @package    gesseh
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseGessehParam extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('gesseh_param');
        $this->hasColumn('titre', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 255,
             ));
        $this->hasColumn('valeur', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 255,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}