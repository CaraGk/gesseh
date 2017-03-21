<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2013-2017 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\SimulationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\DiExtraBundle\Annotation as DI,
    JMS\SecurityExtraBundle\Annotation as Security;
use Gesseh\CoreBundle\Entity\Department,
    Gesseh\SimulationBundle\Entity\Wish,
    Gesseh\SimulationBundle\Form\WishType,
    Gesseh\SimulationBundle\Form\WishHandler;

/**
 * Simulation controller
 *
 * @Route("/simulation")
 */
class SimulationController extends Controller
{
    /** @DI\Inject */
    private $session;

    /** @DI\Inject("doctrine.orm.entity_manager") */
    private $em;

    /** @DI\Inject("fos_user.user_manager") */
    private $um;

    /** @DI\Inject("kdb_parameters.manager") */
    private $pm;

    /**
     * @Route("/", name="GSimul_SIndex")
     * @Template()
     */
    public function indexAction()
    {
        $user = $this->getUser();
        $simid = $this->getRequest()->get('simid');
        $simstudent = $this->testAdminTakeOver($user, $simid);

        if(!$simstudent)
            throw $this->createNotFoundException('Vous ne participez pas aux simulations. Contacter l\'administrateur du site si vous pensez que cette situation est anormale.');

        $last_period = $this->em->getRepository('GessehCoreBundle:Period')->getLast();
        $wishes = $this->em->getRepository('GessehSimulationBundle:Wish')->getByStudent($simstudent->getStudent(), $last_period->getId());
        $rules = $this->em->getRepository('GessehSimulationBundle:SectorRule')->getForStudent($simstudent, $this->em);
        $missing = $this->em->getRepository('GessehSimulationBundle:Simulation')->countMissing($simstudent);

        $new_wish = new Wish();
        $form = $this->createForm(new WishType($rules), $new_wish);
        $formHandler = new WishHandler($form, $this->get('request'), $this->em, $simstudent);

        if ($formHandler->process()) {
            $this->session->getFlashBag()->add('notice', 'Nouveau vœu : "' . $new_wish->getDepartment() . '" enregistré.');

            return $this->redirect($this->generateUrl('GSimul_SIndex', array('simid' => $simid)));
        }

        if ($simid != null) {
            $simname = $this->em->getRepository('GessehSimulationBundle:Simulation')->find($simid)->getStudent();
        } else {
            $simname = null;
        }

        return array(
            'wishes'     => $wishes,
            'wish_form'  => $form->createView(),
            'simstudent' => $simstudent,
            'missing'    => $missing,
            'simid'      => $simid,
            'simname'    => $simname,
        );
    }

    /**
     * @Route("/{wish_id}/up", name="GSimul_SUp", requirements={"wish_id" = "\d+"})
     */
    public function setRankUpAction($wish_id)
    {
        $user = $this->getUser();
        $simid = $this->getRequest()->get('simid');
        $simstudent = $this->testAdminTakeOver($user, $simid);

        $wish = $this->em->getRepository('GessehSimulationBundle:Wish')->findByStudentAndId($simstudent->getStudent(), $wish_id);

        if(!$wish)
            throw $this->createNotFoundException('Unable to find Wish entity');

        $period = $this->em->getRepository('GessehSimulationBundle:SimulPeriod')->getActive()->getPeriod();

        $rank = $wish->getRank();
        if ($rank > 1) {
            $wishes_before = $this->em ->getRepository('GessehSimulationBundle:Wish')->findByStudentAndRank($simstudent->getStudent(), $rank - 1, $period);
            foreach ($wishes_before as $wish_before) {
                $wish_before->setRank($rank);
                $this->em->persist($wish_before);
                $rank--;
              }
            $wish->setRank($rank);
            $this->em->persist($wish);
            $this->session->getFlashBag()->add('notice', 'Vœu : "' . $wish->getDepartment() . '" mis à jour.');
        } else {
            $this->session->getFlashBag()->add('error', 'Attention : le vœu "' . $wish->getDepartment() . '" est déjà le premier de la liste !');
        }
      $this->em->flush();

      return $this->redirect($this->generateUrl('GSimul_SIndex', array('simid' => $simid)));
    }

    /**
     * @Route("/{wish_id}/down", name="GSimul_SDown", requirements={"wish_id" = "\d+"})
     */
    public function setRankDownAction($wish_id)
    {
        $user = $this->getUser();
        $simid = $this->getRequest()->get('simid');
        $simstudent = $this->testAdminTakeOver($user, $simid);

        $wish = $this->em->getRepository('GessehSimulationBundle:Wish')->findByStudentAndId($simstudent->getStudent(), $wish_id);

        if(!$wish)
          throw $this->createNotFoundException('Unable to find Wish entity');

        $period = $this->em->getRepository('GessehSimulationBundle:SimulPeriod')->getActive()->getPeriod();

        $rank = $wish->getRank();
        $max_rank = $this->em->getRepository('GessehSimulationBundle:Wish')->getMaxRank($simstudent->getStudent());
        if ($rank < $max_rank) {
          $wishes_after = $this->em ->getRepository('GessehSimulationBundle:Wish')->findByStudentAndRank($simstudent->getStudent(), $rank + 1, $period);
          foreach ($wishes_after as $wish_after) {
            $wish_after->setRank($rank);
            $this->em->persist($wish_after);
            $rank++;
          }
          $wish->setRank($rank);
          $this->em->persist($wish);
          $this->session->getFlashBag()->add('notice', 'Vœu : "' . $wish->getDepartment() . '" mis à jour.');
        } else {
          $this->session->getFlashBag()->add('error', 'Attention : le vœu "' . $wish->getDepartment() . '" est déjà le dernier de la liste !');
        }
        $this->em->flush();

        return $this->redirect($this->generateUrl('GSimul_SIndex', array('simid' => $simid)));
    }

    /**
     * @Route("/{wish_id}/delete", name="GSimul_SDelete", requirements={"wish_id" = "\d+"})
     */
    public function deleteAction($wish_id)
    {
        $user = $this->getUser();
        $simid = $this->getRequest()->get('simid');
        $simstudent = $this->testAdminTakeOver($user, $simid);

        $wish = $this->em->getRepository('GessehSimulationBundle:Wish')->findByStudentAndId($simstudent->getStudent(), $wish_id);

        if(!$wish)
            throw $this->createNotFoundException('Unable to find Wish entity');

        $rank = $wish->getRank();
        $wishes_after = $this->em->getRepository('GessehSimulationBundle:Wish')->findByRankAfter($simstudent->getStudent(), $rank);
        foreach ($wishes_after as $wish_after) {
            $wish_after->setRank($wish_after->getRank()-1);
            $this->em->persist($wish_after);
        }
        $this->em->remove($wish);

        if ($simstudent->countWishes() <= 1) {
            $simstudent->setDepartment(null);
            $simstudent->setExtra(null);
            $this->em->persist($simstudent);
        }

        $this->em->flush();

        $this->session->getFlashBag()->add('notice', 'Vœu : "' . $wish->getDepartment() . '" supprimé.');

        return $this->redirect($this->generateUrl('GSimul_SIndex', array('simid' => $simid)));
    }

    /**
     * @Route("/out", name="GSimul_SGetout")
     */
    public function getoutAction()
    {
        $user = $this->getUser();
        $simid = $this->getRequest()->get('simid');
        $simstudent = $this->testAdminTakeOver($user, $simid);

      $simstudent->setActive(false);
      $simstudent->setDepartment(null);
      $simstudent->setExtra(null);
      $this->em->persist($simstudent);
      $this->em->flush();

      $this->session->getFlashBag()->add('notice', 'Vous ne participez plus à la simulation. Tous vos vœux ont été effacés.');

      return $this->redirect($this->generateUrl('GSimul_SIndex', array('simid' => $simid)));
    }

    /**
     * @Route("/in", name="GSimul_SGetin")
     */
    public function getinAction()
    {
        $user = $this->getUser();
        $simid = $this->getRequest()->get('simid');
        $simstudent = $this->testAdminTakeOver($user, $simid);

      $simstudent->setActive(true);
      $this->em->persist($simstudent);

      $this->em->flush();

      $this->session->getFlashBag()->add('notice', 'Vous pouvez désormais faire vos choix pour la simulation.');

      return $this->redirect($this->generateUrl('GSimul_SIndex', array('simid' => $simid)));
    }

    /**
     * @Route("/sim", name="GSimul_SSim")
     */
    public function simAction()
    {
        $user = $this->getUser();
        $simid = $this->getRequest()->get('simid');
        $simstudent = $this->testAdminTakeOver($user, $simid);

        if (!$this->em->getRepository('GessehSimulationBundle:SimulPeriod')->isSimulationActive())
            throw $this->createNotFoundException('Aucune session de simulation en cours actuellement. Repassez plus tard.');

        $last_period = $this->em->getRepository('GessehSimulationBundle:SimulPeriod')->getLast()->getPeriod();
        $repartitions = $this->em->getRepository('GessehCoreBundle:Repartition')->getByPeriod($last_period);

        foreach ($repartitions as $repartition) {
            $department_table[$repartition->getDepartment()->getId()] = $repartition->getNumber();
            if ($repartition->getCluster() != null) {
                $department_table['cl_'.$repartition->getCluster()][] = $repartition->getDepartment()->getId();
            }
        }

        $this->em->getRepository('GessehSimulationBundle:Simulation')->doSimulation($department_table, $this->em, $last_period);

        $this->session->getFlashBag()->add('notice', 'Les données de la simulation ont été actualisées');

        return $this->redirect($this->generateUrl('GSimul_SIndex', array('simid' => $simid)));
    }

    /**
     * Affiche la liste des poste restants pour l'étudiant
     *
     * @Route("/left", name="GSimul_SLeft")
     * @Template()
     */
    public function listLeftPlacementsAction()
    {
        $user = $this->getUser();
        $simid = $this->getRequest()->get('simid');
        $simstudent = $this->testAdminTakeOver($user, $simid);

      $last_period = $this->em->getRepository('GessehCoreBundle:Period')->getLast();
      $repartitions = $this->em->getRepository('GessehCoreBundle:Repartition')->getAvailable($last_period);
      $left = array();

      $sims = $this->em->getRepository('GessehSimulationBundle:Simulation')->getDepartmentLeftForRank($simstudent->getRank(), $last_period);
      foreach($sims as $sim) {
        foreach($sim->getDepartment()->getRepartitions() as $repartition) {
          if($cluster_name = $repartition->getCluster()) {
            foreach($this->em->getRepository('GessehCoreBundle:Repartition')->getByPeriodAndCluster($last_period, $cluster_name) as $other_repartition) {
              $left[$other_repartition->getDepartment()->getId()] = $sim->getExtra();
            }
          }
        }
        $left[$repartition->getDepartment()->getId()] = $sim->getExtra();
      }

      if ($simid != null) {
        $simname = $this->em->getRepository('GessehSimulationBundle:Simulation')->find($simid)->getStudent();
      } else {
        $simname = null;
      }

      return array(
        'repartitions' => $repartitions,
        'left'         => $left,
        'simid'        => $simid,
        'simname'      => $simname,
      );
    }

    /**
     * Affiche la liste des simulations
     *
     * @Route("/list", name="GSimul_SList")
     * @Template()
     */
    public function listSimulationsAction()
    {
      if (!$this->em->getRepository('GessehSimulationBundle:SimulPeriod')->isSimulationActive())
        throw $this->createNotFoundException('Aucune session de simulation en cours actuellement. Repassez plus tard.');

      $simulations = $this->em->getRepository('GessehSimulationBundle:Simulation')->getAll()->getResult();

      return array(
        'simulations' => $simulations,
      );
    }

    /**
     * Affiche la liste des simulations pour un department donné
     *
     * @Route("/department/{id}", name="GSimul_SListDept")
     * @Template("GessehSimulationBundle:Simulation:listSimulations.html.twig")
     */
    public function listSimulDeptAction(Department $department)
    {
      if (!$this->em->getRepository('GessehSimulationBundle:SimulPeriod')->isSimulationActive())
        throw $this->createNotFoundException('Aucune session de simulation en cours actuellement. Repassez plus tard.');

      $simulations = $this->em->getRepository('GessehSimulationBundle:Simulation')->findByDepartment($department->getId());

      return array(
        'simulations' => $simulations,
      );
    }

    /**
     * Test for admin take over function
     *
     * @return Simstudent
     */
    public function testAdminTakeOver($user, $simid)
    {
        if ($user->hasRole('ROLE_ADMIN') and $simid) {
            return $this->em->getRepository('GessehSimulationBundle:Simulation')->getSimStudent($simid);
        } else {
            if (!$this->em->getRepository('GessehSimulationBundle:SimulPeriod')->isSimulationActive())
                throw $this->createNotFoundException('Aucune session de simulation en cours actuellement. Repassez plus tard.');

            return $this->em->getRepository('GessehSimulationBundle:Simulation')->getByUser($user);
        }
    }

}
