<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2013-2016 Pierre-François Angrand
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
        return $this->onSuccess(($this->form->getData()));
      }
    }

    return false;
  }

  public function onSuccess($results)
  {
      $repartition = $this->em->getRepository('GessehCoreBundle:Repartition')->findOneBy(array('period' => $results['period'], 'department' => $results['department']));
      $period = $this->em->getRepository('GessehCoreBundle:Period')->findOneBy(array('id' => $results['period']));
      $department = $this->em->getRepository('GessehCoreBundle:Department')->findOneBy(array('id' => $results['department']));
      $student = $this->em->getRepository('GessehUserBundle:Student')->findOneBy(array('id' => $results['student']));
      $placement = new Placement();
      $placement->setRepartition($repartition);
      $placement->setStudent($student);
    if($cluster_name = $placement->getRepartition()->getCluster()) {
        $other_repartitions = $this->em->getRepository('GessehCoreBundle:Repartition')->getByPeriodAndCluster($period, $cluster_name);
        foreach ($other_repartitions as $repartition) {
            $placement_cluster = new Placement();
            $placement_cluster->setStudent($placement->getStudent());
            $placement_cluster->setDepartment($repartition->getDepartment());
            $placement_cluster->setPeriod($period());
            $placement_cluster->setRepartition($repartition);
            $this->em->persist($placement_cluster);
        }
    } else {
        $this->em->persist($placement);
    }

    $this->em->flush();

    return $placement;
  }
}
