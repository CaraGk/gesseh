<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2013-2016 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\SimulationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Gesseh\SimulationBundle\Entity\Wish;
use Gesseh\SimulationBundle\Form\WishType;
use Gesseh\SimulationBundle\Form\WishHandler;

/**
 * Simulation controller
 *
 * @Route("/simulation")
 */
class SimulationController extends Controller
{
    /**
     * @Route("/", name="GSimul_SIndex")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $um = $this->container->get('fos_user.user_manager');
        $user = $um->findUserBy(array(
            'username' => $this->get('security.token_storage')->getToken()->getUsername(),
        ));
        $simid = $this->getRequest()->get('simid');
        $simstudent = $this->testAdminTakeOver($em, $user, $simid);

        if(!$simstudent)
            throw $this->createNotFoundException('Vous ne participez pas aux simulations. Contacter l\'administrateur du site si vous pensez que cette situation est anormale.');

        $last_period = $em->getRepository('GessehCoreBundle:Period')->getLast();
        $wishes = $em->getRepository('GessehSimulationBundle:Wish')->getByStudent($simstudent->getStudent(), $last_period->getId());
        $rules = $em->getRepository('GessehSimulationBundle:SectorRule')->getForStudent($simstudent, $em);
        $missing = $em->getRepository('GessehSimulationBundle:Simulation')->countMissing($simstudent);

        $new_wish = new Wish();
        $form = $this->createForm(new WishType($rules), $new_wish);
        $formHandler = new WishHandler($form, $this->get('request'), $em, $simstudent);

        if ($formHandler->process()) {
            $this->get('session')->getFlashBag()->add('notice', 'Nouveau vœu : "' . $new_wish->getDepartment() . '" enregistré.');

            return $this->redirect($this->generateUrl('GSimul_SIndex', array('simid' => $simid)));
        }

        if ($simid != null) {
            $simname = $em->getRepository('GessehSimulationBundle:Simulation')->find($simid)->getStudent();
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
      $em = $this->getDoctrine()->getManager();
        $um = $this->container->get('fos_user.user_manager');
        $user = $um->findUserBy(array(
            'username' => $this->get('security.token_storage')->getToken()->getUsername(),
        ));
        $simid = $this->getRequest()->get('simid');
        $simstudent = $this->testAdminTakeOver($em, $user, $simid);

      $wish = $em->getRepository('GessehSimulationBundle:Wish')->findByStudentAndId($simstudent->getStudent(), $wish_id);

      if(!$wish)
          throw $this->createNotFoundException('Unable to find Wish entity');

      $period = $em->getRepository('GessehSimulationBundle:SimulPeriod')->getActive()->getPeriod();

        $rank = $wish->getRank();
        if ($rank > 1) {
            $wishes_before = $em ->getRepository('GessehSimulationBundle:Wish')->findByStudentAndRank($simstudent->getStudent(), $rank - 1, $period);
            foreach ($wishes_before as $wish_before) {
                $wish_before->setRank($rank);
                $em->persist($wish_before);
                $rank--;
              }
            $wish->setRank($rank);
            $em->persist($wish);
            $this->get('session')->getFlashBag()->add('notice', 'Vœu : "' . $wish->getDepartment() . '" mis à jour.');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Attention : le vœu "' . $wish->getDepartment() . '" est déjà le premier de la liste !');
        }
      $em->flush();

      return $this->redirect($this->generateUrl('GSimul_SIndex', array('simid' => $simid)));
    }

    /**
     * @Route("/{wish_id}/down", name="GSimul_SDown", requirements={"wish_id" = "\d+"})
     */
    public function setRankDownAction($wish_id)
    {
      $em = $this->getDoctrine()->getManager();
        $um = $this->container->get('fos_user.user_manager');
        $user = $um->findUserBy(array(
            'username' => $this->get('security.token_storage')->getToken()->getUsername(),
        ));
        $simid = $this->getRequest()->get('simid');
        $simstudent = $this->testAdminTakeOver($em, $user, $simid);

      $wish = $em->getRepository('GessehSimulationBundle:Wish')->findByStudentAndId($simstudent->getStudent(), $wish_id);

      if(!$wish)
        throw $this->createNotFoundException('Unable to find Wish entity');

      $period = $em->getRepository('GessehSimulationBundle:SimulPeriod')->getActive()->getPeriod();

        $rank = $wish->getRank();
        $max_rank = $em->getRepository('GessehSimulationBundle:Wish')->getMaxRank($simstudent->getStudent());
        if ($rank < $max_rank) {
          $wishes_after = $em ->getRepository('GessehSimulationBundle:Wish')->findByStudentAndRank($simstudent->getStudent(), $rank + 1, $period);
          foreach ($wishes_after as $wish_after) {
            $wish_after->setRank($rank);
            $em->persist($wish_after);
            $rank++;
          }
          $wish->setRank($rank);
          $em->persist($wish);
          $this->get('session')->getFlashBag()->add('notice', 'Vœu : "' . $wish->getDepartment() . '" mis à jour.');
        } else {
          $this->get('session')->getFlashBag()->add('error', 'Attention : le vœu "' . $wish->getDepartment() . '" est déjà le dernier de la liste !');
        }
      $em->flush();

        return $this->redirect($this->generateUrl('GSimul_SIndex', array('simid' => $simid)));
    }

    /**
     * @Route("/{wish_id}/delete", name="GSimul_SDelete", requirements={"wish_id" = "\d+"})
     */
    public function deleteAction($wish_id)
    {
        $em = $this->getDoctrine()->getManager();
        $um = $this->container->get('fos_user.user_manager');
        $user = $um->findUserBy(array(
            'username' => $this->get('security.token_storage')->getToken()->getUsername(),
        ));
        $simid = $this->getRequest()->get('simid');
        $simstudent = $this->testAdminTakeOver($em, $user, $simid);

        $wish = $em->getRepository('GessehSimulationBundle:Wish')->findByStudentAndId($simstudent->getStudent(), $wish_id);

        if(!$wish)
            throw $this->createNotFoundException('Unable to find Wish entity');

        $rank = $wish->getRank();
        $wishes_after = $em->getRepository('GessehSimulationBundle:Wish')->findByRankAfter($simstudent->getStudent(), $rank);
        foreach ($wishes_after as $wish_after) {
            $wish_after->setRank($wish_after->getRank()-1);
            $em->persist($wish_after);
        }
        $em->remove($wish);

        if ($simstudent->countWishes() <= 1) {
            $simstudent->setDepartment(null);
            $simstudent->setExtra(null);
            $em->persist($simstudent);
        }

        $em->flush();

        $this->get('session')->getFlashBag()->add('notice', 'Vœu : "' . $wish->getDepartment() . '" supprimé.');

        return $this->redirect($this->generateUrl('GSimul_SIndex', array('simid' => $simid)));
    }

    /**
     * @Route("/out", name="GSimul_SGetout")
     */
    public function getoutAction()
    {
      $em = $this->getDoctrine()->getManager();
        $um = $this->container->get('fos_user.user_manager');
        $user = $um->findUserBy(array(
            'username' => $this->get('security.token_storage')->getToken()->getUsername(),
        ));
        $simid = $this->getRequest()->get('simid');
        $simstudent = $this->testAdminTakeOver($em, $user, $simid);

      $simstudent->setActive(false);
      $simstudent->setDepartment(null);
      $simstudent->setExtra(null);
      $em->persist($simstudent);
      $em->flush();

      $this->get('session')->getFlashBag()->add('notice', 'Vous ne participez plus à la simulation. Tous vos vœux ont été effacés.');

      return $this->redirect($this->generateUrl('GSimul_SIndex', array('simid' => $simid)));
    }

    /**
     * @Route("/in", name="GSimul_SGetin")
     */
    public function getinAction()
    {
      $em = $this->getDoctrine()->getManager();
        $um = $this->container->get('fos_user.user_manager');
        $user = $um->findUserBy(array(
            'username' => $this->get('security.token_storage')->getToken()->getUsername(),
        ));
        $simid = $this->getRequest()->get('simid');
        $simstudent = $this->testAdminTakeOver($em, $user, $simid);

      $simstudent->setActive(true);
      $em->persist($simstudent);

      $em->flush();

      $this->get('session')->getFlashBag()->add('notice', 'Vous pouvez désormais faire vos choix pour la simulation.');

      return $this->redirect($this->generateUrl('GSimul_SIndex', array('simid' => $simid)));
    }

    /**
     * @Route("/sim", name="GSimul_SSim")
     */
    public function simAction()
    {
        $em = $this->getDoctrine()->getManager();
        $um = $this->container->get('fos_user.user_manager');
        $user = $um->findUserBy(array(
            'username' => $this->get('security.token_storage')->getToken()->getUsername(),
        ));
        $simid = $this->getRequest()->get('simid');
        $simstudent = $this->testAdminTakeOver($em, $user, $simid);

        if (!$em->getRepository('GessehSimulationBundle:SimulPeriod')->isSimulationActive())
            throw $this->createNotFoundException('Aucune session de simulation en cours actuellement. Repassez plus tard.');

        $last_period = $em->getRepository('GessehSimulationBundle:SimulPeriod')->getLast()->getPeriod();
        $repartitions = $em->getRepository('GessehCoreBundle:Repartition')->getByPeriod($last_period->getId());

        foreach ($repartitions as $repartition) {
            $department_table[$repartition->getDepartment()->getId()] = $repartition->getNumber();
            if ($repartition->getCluster() != null) {
                $department_table['cl_'.$repartition->getCluster()][] = $repartition->getDepartment()->getId();
            }
        }

        $em->getRepository('GessehSimulationBundle:Simulation')->doSimulation($department_table, $em, $last_period);

        $this->get('session')->getFlashBag()->add('notice', 'Les données de la simulation ont été actualisées');

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
      $em = $this->getDoctrine()->getManager();
        $um = $this->container->get('fos_user.user_manager');
        $user = $um->findUserBy(array(
            'username' => $this->get('security.token_storage')->getToken()->getUsername(),
        ));
        $simid = $this->getRequest()->get('simid');
        $simstudent = $this->testAdminTakeOver($em, $user, $simid);

      $last_period = $em->getRepository('GessehCoreBundle:Period')->getLast();
      $repartitions = $em->getRepository('GessehCoreBundle:Repartition')->getAvailable($last_period);
      $left = array();

      $sims = $em->getRepository('GessehSimulationBundle:Simulation')->getDepartmentLeftForRank($simstudent->getRank(), $last_period);
      foreach($sims as $sim) {
        $extra = $sim->extra;
        foreach($sim->getDepartment()->getRepartitions() as $repartition) {
          if($cluster_name = $repartition->getCluster()) {
            foreach($em->getRepository('GessehCoreBundle:Repartition')->getByPeriodAndCluster($last_period, $cluster_name) as $other_repartition) {
              $left[$other_repartition->getDepartment()->getId()] = $extra;
            }
          }
        }
        $left[$repartition->getDepartment()->getId()] = $extra;
      }

      if ($simid != null) {
        $simname = $em->getRepository('GessehSimulationBundle:Simulation')->find($simid)->getStudent();
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
      $em = $this->getDoctrine()->getManager();

      if (!$em->getRepository('GessehSimulationBundle:SimulPeriod')->isSimulationActive())
        throw $this->createNotFoundException('Aucune session de simulation en cours actuellement. Repassez plus tard.');

      $simulations = $em->getRepository('GessehSimulationBundle:Simulation')->getAll()->getResult();

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
    public function listSimulDeptAction($id)
    {
      $em = $this->getDoctrine()->getManager();

      if (!$em->getRepository('GessehSimulationBundle:SimulPeriod')->isSimulationActive())
        throw $this->createNotFoundException('Aucune session de simulation en cours actuellement. Repassez plus tard.');

      $simulations = $em->getRepository('GessehSimulationBundle:Simulation')->findByDepartment($id);

      return array(
        'simulations' => $simulations,
      );
    }

    /**
     * Test for admin take over function
     *
     * @return Simstudent
     */
    public function testAdminTakeOver($em, $user, $simid)
    {
        if ($user->hasRole('ROLE_ADMIN') and $simid) {
            return $em->getRepository('GessehSimulationBundle:Simulation')->getSimStudent($simid);
        } else {
            if (!$em->getRepository('GessehSimulationBundle:SimulPeriod')->isSimulationActive())
                throw $this->createNotFoundException('Aucune session de simulation en cours actuellement. Repassez plus tard.');

            return $em->getRepository('GessehSimulationBundle:Simulation')->getByUser($user);
        }
    }

}
