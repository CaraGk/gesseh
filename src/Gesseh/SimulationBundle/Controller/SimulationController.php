<?php

namespace Gesseh\SimulationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Gesseh\SimulationBundle\Entity\Wish;
use Gesseh\SimulationBundle\Form\WishType;
use Gesseh\SimulationBundle\Form\WishHandler;

/**
 * @Route("/u/s")
 */
class SimulationController extends Controller
{
    /**
     * @Route("/", name="GSimulation_SIndex")
     * @Template()
     */
    public function indexAction()
    {
      $em = $this->getDoctrine()->getEntityManager();

      if (!$em->getRepository('GessehSimulationBundle:SimulPeriod')->isSimulationActive())
        throw $this->createNotFoundException('Aucune session de simulation en cours actuellement. Repassez plus tard.');

      $user = $this->get('security.context')->getToken()->getUsername();
      $simstudent = $em->getRepository('GessehSimulationBundle:Simulation')->getByUsername($user);
      $rules = $em->getRepository('GessehSimulationBundle:SectorRule')->getForStudent($simstudent);
      $wishes = $em->getRepository('GessehSimulationBundle:Wish')->getByStudent($simstudent->getStudent());
      $missing = $em->getRepository('GessehSimulationBundle:Simulation')->countMissing($simstudent);

      $new_wish = new Wish();
      $form = $this->createForm(new WishType($rules), $new_wish);
      $formHandler = new WishHandler($form, $this->get('request'), $em, $simstudent);

      if($formHandler->process()) {
        $this->get('session')->setFlash('notice', 'Nouveau vœu : "' . $new_wish->getDepartment() . '" enregistré.');
        return $this->redirect($this->generateUrl('GSimulation_SIndex'));
      }

      return array(
        'wishes'     => $wishes,
        'wish_form'  => $form->createView(),
        'simstudent' => $simstudent,
        'missing'    => $missing,
      );
    }

    /**
     * @Route("/{wish_id}/up", name="GSimulation_SUp", requirements={"wish_id" = "\d+"})
     */
    public function setRankUpAction($wish_id)
    {
      $em = $this->getDoctrine()->getEntityManager();

      if (!$em->getRepository('GessehSimulationBundle:SimulPeriod')->isSimulationActive())
        throw $this->createNotFoundException('Aucune session de simulation en cours actuellement. Repassez plus tard.');

      $user = $this->get('security.context')->getToken()->getUsername();
      $simstudent = $em->getRepository('GessehSimulationBundle:Simulation')->getByUsername($user);
      $wish = $em->getRepository('GessehSimulationBundle:Wish')->findByStudentAndId($simstudent->getStudent(), $wish_id);

      if(!$wish)
        throw $this->createNotFoundException('Unable to find Wish entity');

      $rank = $wish->getRank();
      if($rank > 1) {
        $rank--;
        $wish_before = $em ->getRepository('GessehSimulationBundle:Wish')->findByStudentAndRank($simstudent->getStudent(), $rank);
        $wish_before->setRank($rank + 1);
        $wish->setRank($rank);
        $em->persist($wish_before);
        $em->persist($wish);
        $em->flush();
        $this->get('session')->setFlash('notice', 'Vœu : "' . $wish->getDepartment() . '" mis à jour.');
      } else {
        $this->get('session')->setFlash('error', 'Attention : le vœu "' . $wish->getDepartment() . '" est déjà le premier de la liste !');
      }
      return $this->redirect($this->generateUrl('GSimulation_SIndex'));
    }

    /**
     * @Route("/{wish_id}/down", name="GSimulation_SDown", requirements={"wish_id" = "\d+"})
     */
    public function setRankDownAction($wish_id)
    {
      $em = $this->getDoctrine()->getEntityManager();

      if (!$em->getRepository('GessehSimulationBundle:SimulPeriod')->isSimulationActive())
        throw $this->createNotFoundException('Aucune session de simulation en cours actuellement. Repassez plus tard.');

      $user = $this->get('security.context')->getToken()->getUsername();
      $simstudent = $em->getRepository('GessehSimulationBundle:Simulation')->getByUsername($user);
      $wish = $em->getRepository('GessehSimulationBundle:Wish')->findByStudentAndId($simstudent->getStudent(), $wish_id);

      if(!$wish)
        throw $this->createNotFoundException('Unable to find Wish entity');

      $rank = $wish->getRank();
      $max_rank = $em->getRepository('GessehSimulationBundle:Wish')->getMaxRank($simstudent->getStudent());
      if($rank < $max_rank) {
        $rank++;
        $wish_after = $em ->getRepository('GessehSimulationBundle:Wish')->findByStudentAndRank($simstudent->getStudent(), $rank);
        $wish_after->setRank($rank - 1);
        $wish->setRank($rank);
        $em->persist($wish_after);
        $em->persist($wish);
        $em->flush();
        $this->get('session')->setFlash('notice', 'Vœu : "' . $wish->getDepartment() . '" mis à jour.');
      } else {
        $this->get('session')->setFlash('error', 'Attention : le vœu "' . $wish->getDepartment() . '" est déjà le dernier de la liste !');
      }
      return $this->redirect($this->generateUrl('GSimulation_SIndex'));
    }

    /**
     * @Route("/{wish_id}/d", name="GSimulation_SDelete", requirements={"wish_id" = "\d+"})
     */
    public function deleteAction($wish_id)
    {
      $em = $this->getDoctrine()->getEntityManager();

      if (!$em->getRepository('GessehSimulationBundle:SimulPeriod')->isSimulationActive())
        throw $this->createNotFoundException('Aucune session de simulation en cours actuellement. Repassez plus tard.');

      $user = $this->get('security.context')->getToken()->getUsername();
      $simstudent = $em->getRepository('GessehSimulationBundle:Simulation')->getByUsername($user);
      $wish = $em->getRepository('GessehSimulationBundle:Wish')->findByStudentAndId($simstudent->getStudent(), $wish_id);

      if(!$wish)
        throw $this->createNotFoundException('Unable to find Wish entity');

      $rank = $wish->getRank();
      $wishes_after = $em->getRepository('GessehSimulationBundle:Wish')->findByRankAfter($simstudent->getStudent(), $rank);
      foreach($wishes_after as $wish_after) {
        $wish_after->setRank($wish_after->getRank()-1);
        $em->persist($wish_after);
      }
      $em->remove($wish);

      if($simstudent->countWishes() <= 1) {
        $simstudent->setDepartment(null);
        $simstudent->setExtra(null);
        $em->persist($simstudent);
      }

      $em->flush();

      $this->get('session')->setFlash('notice', 'Vœu : "' . $wish->getDepartment() . '" supprimé.');
      return $this->redirect($this->generateUrl('GSimulation_SIndex'));
    }

    /**
     * @Route("/out", name="GSimulation_SGetout")
     */
    public function getoutAction()
    {
      $em = $this->getDoctrine()->getEntityManager();

      if (!$em->getRepository('GessehSimulationBundle:SimulPeriod')->isSimulationActive())
        throw $this->createNotFoundException('Aucune session de simulation en cours actuellement. Repassez plus tard.');

      $user = $this->get('security.context')->getToken()->getUsername();
      $simstudent = $em->getRepository('GessehSimulationBundle:Simulation')->getByUsername($user);

      $simstudent->setActive(false);
      $simstudent->setDepartment(null);
      $simstudent->setExtra(null);
/*      if($simstudent->countWishes() > 0) {
        foreach($simstudent->getWishes() as $wish) {
          var_dump($wish);
          $em->remove($wish);
        }
      }
*/
      $em->persist($simstudent);
      $em->flush();

      $this->get('session')->setFlash('notice', 'Vous ne participez plus à la simulation. Tous vos vœux ont été effacés.');
      return $this->redirect($this->generateUrl('GSimulation_SIndex'));
    }

    /**
     * @Route("/in", name="GSimulation_SGetin")
     */
    public function getinAction()
    {
      $em = $this->getDoctrine()->getEntityManager();

      if (!$em->getRepository('GessehSimulationBundle:SimulPeriod')->isSimulationActive())
        throw $this->createNotFoundException('Aucune session de simulation en cours actuellement. Repassez plus tard.');

      $user = $this->get('security.context')->getToken()->getUsername();
      $simstudent = $em->getRepository('GessehSimulationBundle:Simulation')->getByUsername($user);

      $simstudent->setActive(true);
      $em->persist($simstudent);

      $em->flush();

      $this->get('session')->setFlash('notice', 'Vous pouvez désormais faire vos choix pour la simulation.');
      return $this->redirect($this->generateUrl('GSimulation_SIndex'));
    }

    /**
     * @Route("/sim", name="GSimulation_SSim")
     */
    public function simAction()
    {
      $em = $this->getDoctrine()->getEntityManager();

      if (!$em->getRepository('GessehSimulationBundle:SimulPeriod')->isSimulationActive())
        throw $this->createNotFoundException('Aucune session de simulation en cours actuellement. Repassez plus tard.');

      $departments = $em->getRepository('GessehCoreBundle:Department')->findAll();

      foreach($departments as $department) {
        $department_table[$department->getId()] = $department->getNumber();
      }

      $em->getRepository('GessehSimulationBundle:Simulation')->doSimulation($department_table, $em);

      $this->get('session')->setFlash('notice', 'Les données de la simulation ont été actualisées');
      return $this->redirect($this->generateUrl('GSimulation_SIndex'));
    }

    /**
     * Affiche la liste des simulations
     *
     * @Route("/list", name="GSimul_SList")
     * @Template()
     */
    public function listSimulationsAction()
    {
      $em = $this->getDoctrine()->getEntityManager();

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
     * @Route("/list/{id}", name="GSimul_SListDept")
     * @Template("GessehSimulationBundle:Simulation:listSimulations.html.twig")
     */
    public function listSimulDeptAction($id)
    {
      $em = $this->getDoctrine()->getEntityManager();

      if (!$em->getRepository('GessehSimulationBundle:SimulPeriod')->isSimulationActive())
        throw $this->createNotFoundException('Aucune session de simulation en cours actuellement. Repassez plus tard.');

      $simulations = $em->getRepository('GessehSimulationBundle:Simulation')->findByDepartment($id);

      return array(
        'simulations' => $simulations,
      );
    }
}
