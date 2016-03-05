<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Gesseh\UserBundle\Entity\Grade;
use Gesseh\UserBundle\Form\GradeType;
use Gesseh\UserBundle\Form\GradeHandler;

/**
 * StudentAdmin controller.
 *
 * @Route("/admin/grade")
 */
class GradeAdminController extends Controller
{
  /**
   * @Route("/", name="GUser_GAIndex")
   * @Template()
   */
  public function indexAction()
  {
    $em = $this->getDoctrine()->getManager();
    $grades = $em->getRepository('GessehUserBundle:Grade')->getAll();

    return array(
      'grades'       => $grades,
      'grade_id'     => null,
      'grade_form'   => null,
    );
  }

  /**
   * @Route("/new", name="GUser_GANew")
   * @Template("GessehUserBundle:GradeAdmin:index.html.twig")
   */
  public function newAction()
  {
    $em = $this->getDoctrine()->getManager();
    $grades = $em->getRepository('GessehUserBundle:Grade')->getAll();

    $grade = new Grade();
    $grade->setRank($em->getRepository('GessehUserBundle:Grade')->getLastActiveRank() + 1);
    $form = $this->createForm(new GradeType(), $grade);
    $formHandler = new GradeHandler($form, $this->get('request'), $em);

    if ( $formHandler->process() ) {
      $this->get('session')->getFlashBag()->add('notice', 'Promotion "' . $grade . '" enregistrée.');

      return $this->redirect($this->generateUrl('GUser_GAIndex'));
    }

    return array(
      'grades'       => $grades,
      'grade_id'     => null,
      'grade_form'   => $form->createView(),
    );
  }

  /**
   * @Route("/{id}/edit", name="GUser_GAEdit", requirements={"id" = "\d+"})
   * @Template("GessehUserBundle:GradeAdmin:index.html.twig")
   */
  public function editAction($id)
  {
    $em = $this->getDoctrine()->getManager();
    $grades = $em->getRepository('GessehUserBundle:Grade')->getAll();

    $grade = $em->getRepository('GessehUserBundle:Grade')->find($id);

    if( !$grade )
      throw $this->createNotFoundException('Unable to find Grade entity.');

    $form = $this->createForm(new GradeType(), $grade);
    $formHandler = new GradeHandler($form, $this->get('request'), $em);

    if ( $formHandler->process() ) {
      $this->get('session')->getFlashBag()->add('notice', 'Promotion "' . $grade . '" modifiée.');

      return $this->redirect($this->generateUrl('GUser_GAIndex'));
    }

    return array(
      'grades'       => $grades,
      'grade_id'     => $id,
      'grade_form'   => $form->createView(),
    );
  }

  /**
   * @Route("/{id}/delete", name="GUser_GADelete", requirements={"id" = "\d+"})
   */
  public function deleteGradeAction($id)
  {
    $em = $this->getDoctrine()->getManager();
    $grade = $em->getRepository('GessehUserBundle:Grade')->find($id);

    if( !$grade )
      throw $this->createNotFoundException('Unable to find Grade entity.');

    if ($rules = $em->getRepository('GessehSimulationBundle:SectorRule')->getByGrade($id)) {
        foreach ($rules as $rule) {
            $em->remove($rule);
            $this->get('session')->getFlashBag()->add('notice', 'Règle "' . $rule . '" supprimée.');
        }
    }

    $em->remove($grade);
    $em->flush();

    $this->get('session')->getFlashBag()->add('notice', 'Promotion "' . $grade . '" supprimée.');

    return $this->redirect($this->generateUrl('GUser_GAIndex'));
  }

  /**
   * @Route("/next", name="GUser_GAUpdateNext")
   */
  public function updateAllStudentsToNextAction()
  {
    $em = $this->getDoctrine()->getManager();
    $grades = $em->getRepository('GessehUserBundle:Grade')->getAllActiveInverted();

    foreach ($grades as $grade) {
      $next_grade = $em->getRepository('GessehUserBundle:Grade')->getNext($grade->getRank());
      if (null !== $next_grade) {
        $em->getRepository('GessehUserBundle:Student')->setGradeUp($grade->getId(), $next_grade->getId());
      }
    }

    $this->get('session')->getFlashBag()->add('notice', 'Étudiants passés dans la promotion supérieure.');

    return $this->redirect($this->generateUrl('GUser_GAIndex'));
  }
}
