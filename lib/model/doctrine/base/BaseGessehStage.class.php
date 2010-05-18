<?php

/**
 * BaseGessehStage
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $terrain_id
 * @property integer $periode_id
 * @property integer $etudiant_id
 * @property boolean $is_active
 * @property GessehTerrain $GessehTerrain
 * @property GessehPeriode $GessehPeriode
 * @property GessehEtudiant $GessehEtudiant
 * @property Doctrine_Collection $GessehEval
 * 
 * @method integer             getTerrainId()      Returns the current record's "terrain_id" value
 * @method integer             getPeriodeId()      Returns the current record's "periode_id" value
 * @method integer             getEtudiantId()     Returns the current record's "etudiant_id" value
 * @method boolean             getIsActive()       Returns the current record's "is_active" value
 * @method GessehTerrain       getGessehTerrain()  Returns the current record's "GessehTerrain" value
 * @method GessehPeriode       getGessehPeriode()  Returns the current record's "GessehPeriode" value
 * @method GessehEtudiant      getGessehEtudiant() Returns the current record's "GessehEtudiant" value
 * @method Doctrine_Collection getGessehEval()     Returns the current record's "GessehEval" collection
 * @method GessehStage         setTerrainId()      Sets the current record's "terrain_id" value
 * @method GessehStage         setPeriodeId()      Sets the current record's "periode_id" value
 * @method GessehStage         setEtudiantId()     Sets the current record's "etudiant_id" value
 * @method GessehStage         setIsActive()       Sets the current record's "is_active" value
 * @method GessehStage         setGessehTerrain()  Sets the current record's "GessehTerrain" value
 * @method GessehStage         setGessehPeriode()  Sets the current record's "GessehPeriode" value
 * @method GessehStage         setGessehEtudiant() Sets the current record's "GessehEtudiant" value
 * @method GessehStage         setGessehEval()     Sets the current record's "GessehEval" collection
 * 
 * @package    gesseh
 * @subpackage model
 * @author     Pierre-FrançoisPilouAngrand
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseGessehStage extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('gesseh_stage');
        $this->hasColumn('terrain_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('periode_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('etudiant_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('is_active', 'boolean', null, array(
             'type' => 'boolean',
             'notnull' => true,
             'default' => 1,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('GessehTerrain', array(
             'local' => 'terrain_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $this->hasOne('GessehPeriode', array(
             'local' => 'periode_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $this->hasOne('GessehEtudiant', array(
             'local' => 'etudiant_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $this->hasMany('GessehEval', array(
             'local' => 'id',
             'foreign' => 'stage_id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}