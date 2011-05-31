<?php

/**
 * GessehTerrain
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 *
 * @package    gesseh
 * @subpackage model
 * @author     Pierre-François "Pilou" Angrand
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class GessehTerrain extends BaseGessehTerrain
{
  public function __toString()
  {
    return sprintf('%s à %s (%s)', $this->getTitre(), $this->getGessehHopital()->getNom(), $this->getPatron());
  }

}
