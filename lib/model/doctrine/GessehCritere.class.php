<?php

/**
 * GessehCritere
 *
 * @package    gesseh
 * @subpackage model
 * @author     PierreFrancoisPilouAngrand
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class GessehCritere extends BaseGessehCritere
{
  public function __toString()
  {
    return $this->getTitre();
  }
}
