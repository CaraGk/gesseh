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
      $user = $this->get('security.context')->getToken()->getUsername();
      $simstudent = $em->getRepository('GessehSimulationBundle:Simulation')->getByUsername($user);
      $wishes = $em->getRepository('GessehSimulationBundle:Wish')->getByStudent($simstudent->getStudent());

      $new_wish = new Wish();
      $form = $this->createForm(new WishType($user), $new_wish);
      $formHandler = new WishHandler($form, $this->get('request'), $em, $simstudent);

      if($formHandler->process()) {
        $this->get('session')->setFlash('notice', 'Nouveau vœu : "' . $new_wish->getDepartment() . '" enregistré.');
        return $this->redirect($this->generateUrl('GSimulation_SIndex'));
      }

      return array(
        'wishes'    => $wishes,
        'wish_form' => $form->createView(),
      );
    }

    /**
     * @Route("/{wish_id}/up", name="GSimulation_SUp", requirements={"wish_id" = "\d+"})
     */
    public function setRankUpAction($wish_id)
    {
      $em = $this->getDoctrine()->getEntityManager();
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
}
