<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <caragk@angrand.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
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

      if (!$em->getRepository('GessehSimulationBundle:SimulPeriod')->isSimulationActive())
        throw $this->createNotFoundException('Aucune session de simulation en cours actuellement. Repassez plus tard.');

      $user = $this->get('security.context')->getToken()->getUsername();
      $simstudent = $em->getRepository('GessehSimulationBundle:Simulation')->getByUsername($user);
      $wishes = $em->getRepository('GessehSimulationBundle:Wish')->getByStudent($simstudent->getStudent());
      $rules = $em->getRepository('GessehSimulationBundle:SectorRule')->getForStudent($simstudent);
      $missing = $em->getRepository('GessehSimulationBundle:Simulation')->countMissing($simstudent);

      $new_wish = new Wish();
      $form = $this->createForm(new WishType($rules), $new_wish);
      $formHandler = new WishHandler($form, $this->get('request'), $em, $simstudent);

      if ($formHandler->process()) {
        $this->get('session')->getFlashBag()->add('notice', 'Nouveau vœu : "' . $new_wish->getDepartment() . '" enregistré.');

        return $this->redirect($this->generateUrl('GSimul_SIndex'));
      }

      return array(
        'wishes'     => $wishes,
        'wish_form'  => $form->createView(),
        'simstudent' => $simstudent,
        'missing'    => $missing,
      );
    }

    /**
     * @Route("/{wish_id}/up", name="GSimul_SUp", requirements={"wish_id" = "\d+"})
     */
    public function setRankUpAction($wish_id)
    {
      $em = $this->getDoctrine()->getManager();

      if (!$em->getRepository('GessehSimulationBundle:SimulPeriod')->isSimulationActive())
        throw $this->createNotFoundException('Aucune session de simulation en cours actuellement. Repassez plus tard.');

      $user = $this->get('security.context')->getToken()->getUsername();
      $simstudent = $em->getRepository('GessehSimulationBundle:Simulation')->getByUsername($user);
      $wish = $em->getRepository('GessehSimulationBundle:Wish')->findByStudentAndId($simstudent->getStudent(), $wish_id);

      if(!$wish)
          throw $this->createNotFoundException('Unable to find Wish entity');

        $rank = $wish->getRank();
        if ($rank > 1) {
            $wishes_before = $em ->getRepository('GessehSimulationBundle:Wish')->findByStudentAndRank($simstudent->getStudent(), $rank - 1);
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

      return $this->redirect($this->generateUrl('GSimul_SIndex'));
    }

    /**
     * @Route("/{wish_id}/down", name="GSimul_SDown", requirements={"wish_id" = "\d+"})
     */
    public function setRankDownAction($wish_id)
    {
      $em = $this->getDoctrine()->getManager();

      if (!$em->getRepository('GessehSimulationBundle:SimulPeriod')->isSimulationActive())
        throw $this->createNotFoundException('Aucune session de simulation en cours actuellement. Repassez plus tard.');

      $user = $this->get('security.context')->getToken()->getUsername();
      $simstudent = $em->getRepository('GessehSimulationBundle:Simulation')->getByUsername($user);
      $wish = $em->getRepository('GessehSimulationBundle:Wish')->findByStudentAndId($simstudent->getStudent(), $wish_id);

      if(!$wish)
        throw $this->createNotFoundException('Unable to find Wish entity');

        $rank = $wish->getRank();
        $max_rank = $em->getRepository('GessehSimulationBundle:Wish')->getMaxRank($simstudent->getStudent());
        if ($rank < $max_rank) {
          $wishes_after = $em ->getRepository('GessehSimulationBundle:Wish')->findByStudentAndRank($simstudent->getStudent(), $rank + 1);
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

      return $this->redirect($this->generateUrl('GSimul_SIndex'));
    }

    /**
     * @Route("/{wish_id}/delete", name="GSimul_SDelete", requirements={"wish_id" = "\d+"})
     */
    public function deleteAction($wish_id)
    {
        $em = $this->getDoctrine()->getManager();

        if (!$em->getRepository('GessehSimulationBundle:SimulPeriod')->isSimulationActive())
            throw $this->createNotFoundException('Aucune session de simulation en cours actuellement. Repassez plus tard.');

        $user = $this->get('security.context')->getToken()->getUsername();
        $simstudent = $em->getRepository('GessehSimulationBundle:Simulation')->getByUsername($user);
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

        return $this->redirect($this->generateUrl('GSimul_SIndex'));
    }

    /**
     * @Route("/out", name="GSimul_SGetout")
     */
    public function getoutAction()
    {
      $em = $this->getDoctrine()->getManager();

      if (!$em->getRepository('GessehSimulationBundle:SimulPeriod')->isSimulationActive())
        throw $this->createNotFoundException('Aucune session de simulation en cours actuellement. Repassez plus tard.');

      $user = $this->get('security.context')->getToken()->getUsername();
      $simstudent = $em->getRepository('GessehSimulationBundle:Simulation')->getByUsername($user);

      $simstudent->setActive(false);
      $simstudent->setDepartment(null);
      $simstudent->setExtra(null);
/*      if ($simstudent->countWishes() > 0) {
        foreach ($simstudent->getWishes() as $wish) {
          var_dump($wish);
          $em->remove($wish);
        }
      }
*/
      $em->persist($simstudent);
      $em->flush();

      $this->get('session')->getFlashBag()->add('notice', 'Vous ne participez plus à la simulation. Tous vos vœux ont été effacés.');

      return $this->redirect($this->generateUrl('GSimul_SIndex'));
    }

    /**
     * @Route("/in", name="GSimul_SGetin")
     */
    public function getinAction()
    {
      $em = $this->getDoctrine()->getManager();

      if (!$em->getRepository('GessehSimulationBundle:SimulPeriod')->isSimulationActive())
        throw $this->createNotFoundException('Aucune session de simulation en cours actuellement. Repassez plus tard.');

      $user = $this->get('security.context')->getToken()->getUsername();
      $simstudent = $em->getRepository('GessehSimulationBundle:Simulation')->getByUsername($user);

      $simstudent->setActive(true);
      $em->persist($simstudent);

      $em->flush();

      $this->get('session')->getFlashBag()->add('notice', 'Vous pouvez désormais faire vos choix pour la simulation.');

      return $this->redirect($this->generateUrl('GSimul_SIndex'));
    }

    /**
     * @Route("/sim", name="GSimul_SSim")
     */
    public function simAction()
    {
      $em = $this->getDoctrine()->getManager();

      if (!$em->getRepository('GessehSimulationBundle:SimulPeriod')->isSimulationActive())
        throw $this->createNotFoundException('Aucune session de simulation en cours actuellement. Repassez plus tard.');

      $departments = $em->getRepository('GessehCoreBundle:Department')->findAll();

      foreach ($departments as $department) {
        $department_table[$department->getId()] = $department->getNumber();
        if ($department->getCluster() != null) {
          $department_table['cl_'.$department->getCluster()][] = $department->getId();
        }
      }

      $em->getRepository('GessehSimulationBundle:Simulation')->doSimulation($department_table, $em);

      $this->get('session')->getFlashBag()->add('notice', 'Les données de la simulation ont été actualisées');

      return $this->redirect($this->generateUrl('GSimul_SIndex'));
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

      if (!$em->getRepository('GessehSimulationBundle:SimulPeriod')->isSimulationActive())
        throw $this->createNotFoundException('Aucune session de simulation en cours actuellement. Repassez plus tard.');

      $user = $this->get('security.context')->getToken()->getUsername();
      $simstudent = $em->getRepository('GessehSimulationBundle:Simulation')->getByUsername($user);
      $departments = $em->getRepository('GessehCoreBundle:Department')->getAll();
      $left = array();

      foreach ($departments as $department) {
        $left[$department->getId()] = $department->getNumber();
        if ($sim = $em->getRepository('GessehSimulationBundle:Simulation')->getNumberLeft($department->getId(), $simstudent->getId())) {
          $left[$department->getId()] = $sim->getExtra();
        }
      }

      return array(
        'departments' => $departments,
        'left'        => $left,
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

      $simulations = $em->getRepository('GessehSimulationBundle:Simulation')->findAll();

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
}
