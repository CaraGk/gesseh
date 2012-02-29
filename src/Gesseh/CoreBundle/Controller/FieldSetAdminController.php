<?php

namespace Gesseh\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Gesseh\CoreBundle\Entity\Hospital;
use Gesseh\CoreBundle\Form\HospitalType;
use Gesseh\CoreBundle\Form\HospitalDescriptionType;
use Gesseh\CoreBundle\Form\HospitalHandler;
use Gesseh\CoreBundle\Entity\Sector;
use Gesseh\CoreBundle\Form\SectorType;
use Gesseh\CoreBundle\Form\SectorHandler;
use Gesseh\CoreBundle\Entity\Department;
use Gesseh\CoreBundle\Form\DepartmentDescriptionType;
use Gesseh\CoreBundle\Form\DepartmentHandler;

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
      $this->get('session')->setFlash('notice', 'Hôpital "' . $hospital->getName() . '" enregistré.');
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
      $this->get('session')->setFlash('notice', 'Hôpital "' . $hospital->getName() . '" modifié.');
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
   */
  public function deleteHospitalAction($id)
  {
    $em = $this->getDoctrine()->getEntityManager();
    $hospital = $em->getRepository('GessehCoreBundle:Hospital')->find($id);

    if (!$hospital)
      throw $this->createNotFoundException('Unable to find Hospital entity.');

    $em->remove($hospital);
    $em->flush();

    $this->get('session')->setFlash('notice', 'Hôpital "' . $hospital->getName() . '" supprimé.');
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
      $this->get('session')->setFlash('notice', 'Catégorie "' . $sector->getName() . '" enregistrée.');
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
      $this->get('session')->setFlash('notice', 'Catégorie "' . $sector->getName() . '" modifiée.');
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
   */
  public function deleteSectorAction($id)
  {
    $em = $this->getDoctrine()->getEntityManager();
    $sector = $em->getRepository('GessehCoreBundle:Sector')->find($id);

    if (!$sector)
      throw $this->createNotFoundException('Unable to find Sector entity.');

    $em->remove($sector);
    $em->flush();

    $this->get('session')->setFlash('notice', 'Catégorie "' . $sector->getName() . '" supprimée.');
    return $this->redirect($this->generateUrl('GCore_FSAIndex'));
  }

  /**
   * Edit the description of the Department entity.
   *
   * @Route("/d/{id}", name="GCore_FSAEditDepartmentDescription", requirements={"id" = "\d+"})
   * @Template("GessehCoreBundle:FieldSetAdmin:editDescription.html.twig")
   */
  public function editDepartmentDescriptionAction($id)
  {
    $em = $this->getDoctrine()->getEntityManager();
    $department = $em->getRepository('GessehCoreBundle:Department')->find($id);

    if (!$department)
      throw $this->createNotFoundException('Unable to find Department entity.');

    $editForm = $this->createForm(new DepartmentDescriptionType(), $department);
    $formHandler = new DepartmentHandler($editForm, $this->get('request'), $em);

    if ( $formHandler->process() ) {
      $this->get('session')->setFlash('notice', 'Description du service "' . $department->getName() . '" enregistrée.');
      return $this->redirect($this->generateUrl('GCore_FSAEditDepartmentDescription', array('id' => $id)));
    }

    return array(
      'entity'  => $department,
      'edit_form' => $editForm->createView(),
    );
  }
  /**
   * Edit the description of the Hospital entity.
   *
   * @Route("/h/{id}", name="GCore_FSAEditHospitalDescription", requirements={"id" = "\d+"})
   * @Template("GessehCoreBundle:FieldSetAdmin:editDescription.html.twig")
   */
  public function editHospitalDescriptionAction($id)
  {
    $em = $this->getDoctrine()->getEntityManager();
    $hospital = $em->getRepository('GessehCoreBundle:Hospital')->find($id);

    if (!$hospital)
      throw $this->createNotFoundException('Unable to find Hospital entity.');

    $editForm = $this->createForm(new HospitalDescriptionType(), $hospital);
    $formHandler = new HospitalHandler($editForm, $this->get('request'), $em);

    if ( $formHandler->process() ) {
      $this->get('session')->setFlash('notice', 'Description de l\'hôpital "' . $hospital->getName() . '" enregistrée.');
      return $this->redirect($this->generateUrl('GCore_FSAEditHospitalDescription', array('id' => $id)));
    }

    return array(
      'entity'  => $hospital,
      'edit_form' => $editForm->createView(),
    );
  }
}
