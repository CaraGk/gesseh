<?php

namespace Gesseh\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Gesseh\CoreBundle\Entity\Hospital;
use Gesseh\CoreBundle\Form\HospitalType;
use Gesseh\CoreBundle\Form\HospitalHandler;
use Gesseh\CoreBundle\Entity\Sector;
use Gesseh\CoreBundle\Form\SectorType;
use Gesseh\CoreBundle\Form\SectorHandler;

/**
 * FieldSetAdmin controller.
 *
 * @Route("/admin/f")
 */
class FieldSetAdminController extends Controller
{
  /**
   * Lists all Hospital, Department and Sector entities.
   *
   * @Route("/", name="GCore_FSAIndex")
   * @Template()
   */
  public function indexAction()
  {
    $em = $this->getDoctrine()->getEntityManager();
    $hospitals = $em->getRepository('GessehCoreBundle:Hospital')->findAll();
    $sectors = $em->getRepository('GessehCoreBundle:Sector')->findAll();

    return array(
      'hospitals' => $hospitals,
      'sectors' => $sectors,
    );
  }

  /**
   * Displays a form to create a new Hospital entity.
   *
   * @Route("/h/new", name="GCore_FSANewHospital")
   * @Template("GessehCoreBundle:FieldSetAdmin:editHospital.html.twig")
   */
  public function newHospitalAction()
  {
    $hospital = new Hospital();
    $form   = $this->createForm(new HospitalType(), $hospital);
    $formHandler = new HospitalHandler($form, $this->get('request'), $this->getDoctrine()->getEntityManager());

    if ( $formHandler->process() ) {
      return $this->redirect($this->generateUrl('GCore_FSAIndex'));
    }

    return array(
      'hospital'      => $hospital,
      'hospital_form' => $form->createView(),
      'delete_form'   => null,
    );
  }

  /**
   * Displays a form to edit an existing Hospital entity.
   *
   * @Route("/h/{id}/edit", name="GCore_FSAEditHospital", requirements={"id" = "\d+"})
   * @Template()
   */
  public function editHospitalAction($id)
  {
    $em = $this->getDoctrine()->getEntityManager();
    $hospital = $em->getRepository('GessehCoreBundle:Hospital')->find($id);

    if (!$hospital)
      throw $this->createNotFoundException('Unable to find Hospital entity.');

    $editForm = $this->createForm(new HospitalType(), $hospital);
    $formHandler = new HospitalHandler($editForm, $this->get('request'), $em);
    $deleteForm = $this->createDeleteForm($id);

    if ( $formHandler->process() ) {
      return $this->redirect($this->generateUrl('GCore_FSAEditHospital', array('id' => $id)));
    }

    return array(
      'hospital'      => $hospital,
      'hospital_form' => $editForm->createView(),
      'delete_form'   => $deleteForm->createView(),
    );
  }

  /**
   * Deletes a Hospital entity.
   *
   * @Route("/h/{id}/delete", name="GCore_FSADeleteHospital", requirements={"id" = "\d+"}))
   * @Method("post")
   */
  public function deleteHospitalAction($id)
  {
    $form = $this->createDeleteForm($id);
    $request = $this->getRequest();

    $form->bindRequest($request);

    if ($form->isValid()) {
      $em = $this->getDoctrine()->getEntityManager();
      $hospital = $em->getRepository('GessehCoreBundle:Hospital')->find($id);

      if (!$hospital)
        throw $this->createNotFoundException('Unable to find Hospital entity.');

      $em->remove($hospital);
      $em->flush();
    }

    return $this->redirect($this->generateUrl('GCore_FSAIndex'));
  }

  /**
   * Displays a form to create a new Sector entity.
   *
   * @Route("/s/new", name="GCore_FSANewSector")
   * @Template("GessehCoreBundle:FieldSetAdmin:editSector.html.twig")
   */
  public function newSectorAction()
  {
    $sector = new Sector();
    $form   = $this->createForm(new SectorType(), $sector);
    $formHandler = new SectorHandler($form, $this->get('request'), $this->getDoctrine()->getEntityManager());

    if ( $formHandler->process() ) {
      return $this->redirect($this->generateUrl('GCore_FSAIndex'));
    }

    return array(
     'sector'      => $sector,
     'sector_form' => $form->createView(),
     'delete_form' => null,
    );
  }

  /**
   * Displays a form to edit an existing Sector entity.
   *
   * @Route("/s/{id}/edit", name="GCore_FSAEditSector", requirements={"id" = "\d+"}, defaults={"id" = 0})
   * @Template()
   */
  public function editSectorAction($id)
  {
    $em = $this->getDoctrine()->getEntityManager();

    if ($id) {
      $sector = $em->getRepository('GessehCoreBundle:Sector')->find($id);
      if (!$sector)
        throw $this->createNotFoundException('Unable to find Sector entity.');
      $deleteForm = $this->createDeleteForm($id);
      $render = array('delete_form' => $deleteForm->createView());
    } else {
      $sector = new Sector();
      $render = array('delete_form' => null);
    }

    $editForm = $this->createForm(new SectorType(), $sector);
    $formHandler = new SectorHandler($editForm, $this->get('request'), $em);

    if ( $formHandler->process() ) {
      return $this->redirect($this->generateUrl('GCore_FSAIndex'));
    }

    $render['sector'] = $sector;
    $render['sector_form'] = $editForm->createView();

    return $render;
  }

  /**
   * Deletes a Sector entity.
   *
   * @Route("/s/{id}/delete", name="GCore_FSADeleteSector")
   * @Method("post")
   */
  public function deleteSectorAction($id)
  {
    $form = $this->createDeleteForm($id);
    $request = $this->getRequest();

    $form->bindRequest($request);

    if ($form->isValid()) {
      $em = $this->getDoctrine()->getEntityManager();
      $sector = $em->getRepository('GessehCoreBundle:Sector')->find($id);

      if (!$sector) {
        throw $this->createNotFoundException('Unable to find Sector entity.');
      }

      $em->remove($sector);
      $em->flush();
    }

    return $this->redirect($this->generateUrl('GCore_FSAIndex'));
  }

  private function createDeleteForm($id)
  {
    return $this->createFormBuilder(array('id' => $id))
      ->add('id', 'hidden')
      ->getForm()
    ;
  }

  /**
   * @Route("/d/{id}", name="GCore_FSAEditDepartment", requirements={"id" = "\d+"})
   * @Template()
   */
  public function editDepartmentAction($id)
  {
  }
}
