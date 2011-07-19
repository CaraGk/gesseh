<?php

/**
 * GessehStage
 *
 * @package    gesseh
 * @subpackage model
 * @author     Pierre-François Pilou Angrand
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class GessehStage extends BaseGessehStage
{
  public function __toString()
  {
    return sprintf('%s du %s au %s', $this->getGessehTerrain()->getTitre(), $this->getGessehPeriode()->getDebut(), $this->getGessehPeriode()->getFin());
  }

}
