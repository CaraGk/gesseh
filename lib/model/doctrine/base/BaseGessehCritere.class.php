<?php

/**
 * BaseGessehCritere
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $form
 * @property string $titre
 * @property string $type
 * @property integer $ratio
 * @property Doctrine_Collection $GessehTerrain
 * @property Doctrine_Collection $GessehEval
 * 
 * @method integer             getForm()          Returns the current record's "form" value
 * @method string              getTitre()         Returns the current record's "titre" value
 * @method string              getType()          Returns the current record's "type" value
 * @method integer             getRatio()         Returns the current record's "ratio" value
 * @method Doctrine_Collection getGessehTerrain() Returns the current record's "GessehTerrain" collection
 * @method Doctrine_Collection getGessehEval()    Returns the current record's "GessehEval" collection
 * @method GessehCritere       setForm()          Sets the current record's "form" value
 * @method GessehCritere       setTitre()         Sets the current record's "titre" value
 * @method GessehCritere       setType()          Sets the current record's "type" value
 * @method GessehCritere       setRatio()         Sets the current record's "ratio" value
 * @method GessehCritere       setGessehTerrain() Sets the current record's "GessehTerrain" collection
 * @method GessehCritere       setGessehEval()    Sets the current record's "GessehEval" collection
 * 
 * @package    gesseh
 * @subpackage model
 * @author     Pierre-FrançoisPilouAngrand
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseGessehCritere extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('gesseh_critere');
        $this->hasColumn('form', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('titre', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 255,
             ));
        $this->hasColumn('type', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 255,
             ));
        $this->hasColumn('ratio', 'integer', null, array(
             'type' => 'integer',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('GessehTerrain', array(
             'local' => 'form',
             'foreign' => 'form_id'));

        $this->hasMany('GessehEval', array(
             'local' => 'id',
             'foreign' => 'critere_id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}