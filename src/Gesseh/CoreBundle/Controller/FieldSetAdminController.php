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
   * @Route("/", name="admin_hp")
   * @Template()
   */
  public function indexAction()
  {
    $em = $this->getDoctrine()->getEntityManager();
    $hospitals = $em->getRepository('GessehCoreBundle:Hospital')->findAll();
    $sectors = $em->getRepository('GessehCoreBundle:Sector')->findAll();

    return array(
      'hospitals'     => $hospitals,
      'sectors'       => $sectors,
      'hospital_id'   => null,
      'hospital_form' => null,
      'sector_id'     => null,
      'sector_form'   => null,
    );
  }

  /**
   * Displays a form to create a new Hospital entity.
   *
   * @Route("/h", name="GCore_FSANewHospital")
   * @Template("GessehCoreBundle:FieldSetAdmin:index.html.twig")
   */
  public function newHospitalAction()
  {
    $em = $this->getDoctrine()->getEntityManager();
    $hospitals = $em->getRepository('GessehCoreBundle:Hospital')->findAll();
    $sectors = $em->getRepository('GessehCoreBundle:Sector')->findAll();

    $hospital = new Hospital();
    $form   = $this->createForm(new HospitalType(), $hospital);
    $formHandler = new HospitalHandler($form, $this->get('request'), $this->getDoctrine()->getEntityManager());

    if ( $formHandler->process() ) {
      return $this->redirect($this->generateUrl('GCore_FSAIndex'));
    }

    return array(
      'hospitals'     => $hospitals,
      'sectors'       => $sectors,
      'hospital_id'   => null,
      'hospital_form' => $form->createView(),
      'sector_id'     => null,
      'sector_form'   => null,
    );
  }

  /**
   * Displays a form to edit an existing Hospital entity.
   *
   * @Route("/h/{id}/e", name="GCore_FSAEditHospital", requirements={"id" = "\d+"})
   * @Template("GessehCoreBundle:FieldSetAdmin:index.html.twig")
   */
  public function editHospitalAction($id)
  {
    $em = $this->getDoctrine()->getEntityManager();
    $hospitals = $em->getRepository('GessehCoreBundle:Hospital')->findAll();
    $sectors = $em->getRepository('GessehCoreBundle:Sector')->findAll();

    $hospital = $em->getRepository('GessehCoreBundle:Hospital')->find($id);

    if (!$hospital)
      throw $this->createNotFoundException('Unable to find Hospital entity.');

    $editForm = $this->createForm(new HospitalType(), $hospital);
    $formHandler = new HospitalHandler($editForm, $this->get('request'), $em);

    if ( $formHandler->process() ) {
      return $this->redirect($this->generateUrl('GCore_FSAIndex'));
    }

    return array(
      'hospitals'     => $hospitals,
      'sectors'       => $sectors,
      'hospital_id'   => $id,
      'hospital_form' => $editForm->createView(),
      'sector_id'     => null,
      'sector_form'   => null,
    );
  }

  /**
   * Deletes a Hospital entity.
   *
   * @Route("/h/{id}/d", name="GCore_FSADeleteHospital", requirements={"id" = "\d+"}))
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
   * @Route("/s", name="GCore_FSANewSector")
   * @Template("GessehCoreBundle:FieldSetAdmin:index.html.twig")
   */
  public function newSectorAction()
  {
    $em = $this->getDoctrine()->getEntityManager();
    $hospitals = $em->getRepository('GessehCoreBundle:Hospital')->findAll();
    $sectors = $em->getRepository('GessehCoreBundle:Sector')->findAll();

    $sector = new Sector();

    $editForm = $this->createForm(new SectorType(), $sector);
    $formHandler = new SectorHandler($editForm, $this->get('request'), $em);

    if ( $formHandler->process() ) {
      return $this->redirect($this->generateUrl('GCore_FSAIndex'));
    }

    return array (
      'sectors'       => $sectors,
      'hospitals'     => $hospitals,
      'hospital_id'   => null,
      'hospital_form' => null,
      'sector_id'     => null,
      'sector_form'   => $editForm->createView(),
    );
  }

  /**
   * Displays a form to edit an existing Sector entity.
   *
   * @Route("/s/{id}/e", name="GCore_FSAEditSector", requirements={"id" = "\d+"})
   * @Template("GessehCoreBundle:FieldSetAdmin:index.html.twig")
   */
  public function editSectorAction($id)
  {
    $em = $this->getDoctrine()->getEntityManager();
    $hospitals = $em->getRepository('GessehCoreBundle:Hospital')->findAll();
    $sectors = $em->getRepository('GessehCoreBundle:Sector')->findAll();

    $sector = $em->getRepository('GessehCoreBundle:Sector')->find($id);

    if (!$sector)
        throw $this->createNotFoundException('Unable to find Sector entity.');

    $editForm = $this->createForm(new SectorType(), $sector);
    $formHandler = new SectorHandler($editForm, $this->get('request'), $em);

    if ( $formHandler->process() ) {
      return $this->redirect($this->generateUrl('GCore_FSAIndex'));
    }

    return array (
      'sectors'       => $sectors,
      'hospitals'     => $hospitals,
      'hospital_id'   => null,
      'hospital_form' => null,
      'sector_id'     => $id,
      'sector_form'   => $editForm->createView(),
    );
  }

  /**
   * Deletes a Sector entity.
   *
   * @Route("/s/{id}/d", name="GCore_FSADeleteSector")
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
