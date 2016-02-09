<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\CoreBundle\Form;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Gesseh\CoreBundle\Entity\Placement;

/**
 * PlacementType Handler
 */
class PlacementHandler
{
  private $form;
  private $request;
  private $em;

  public function __construct(Form $form, Request $request, EntityManager $em)
  {
    $this->form    = $form;
    $this->request = $request;
    $this->em      = $em;
  }

  public function process()
  {
    if ( $this->request->getMethod() == 'POST' ) {
      $this->form->bind($this->request);

      if ($this->form->isValid()) {
        $this->onSuccess(($this->form->getData()));

        return true;
      }
    }

    return false;
  }

  public function onSuccess(Placement $placement)
  {
      $period = $placement->getPeriod();
      if ($current_repartition = $placement->getDepartment()->findRepartition($period)) {
          if($cluster_name = $current_repartition->getCluster()) {
              $other_repartitions = $this->em->getRepository('GessehCoreBundle:Repartition')->getByPeriodAndCluster($period->getId(), $cluster_name);
          }

        foreach ($repartitions as $repartition) {
            $placement_cluster = new Placement();
            $placement_cluster->setStudent($placement->getStudent());
            $placement_cluster->setDepartment($repartition->getDepartment());
            $placement_cluster->setPeriod($period());
            $this->em->persist($placement_cluster);
        }
    } else {
        $this->em->persist($placement);
    }

    $this->em->flush();
  }
}
