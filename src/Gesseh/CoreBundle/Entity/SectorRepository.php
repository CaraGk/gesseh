<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <caragk@angrand.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\CoreBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * SectorRepository
 */
class SectorRepository extends EntityRepository
{
  public function listOtherSectors(array $exclude = null)
  {
    $query = $this->createQueryBuilder('s');
    if ($exclude != null) {
      $query->where('s.id NOT IN (' . implode(',', $exclude) . ')');
    }

    return $query;
  }
}
