<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2013-2016 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
use Gesseh\CoreBundle\Form\DepartmentDescriptionType,
    Gesseh\CoreBundle\Form\DepartmentHandler;
use Gesseh\CoreBundle\Entity\Accreditation,
    Gesseh\CoreBundle\Form\AccreditationType,
    Gesseh\CoreBundle\Form\AccreditationHandler;

/**
 * FieldSetAdmin controller.
 *
 * @Route("/admin")
 */
class FieldSetAdminController extends Controller
{
  /**
   * Lists all Sector entities.
   *
   * @Route("/sector", name="GCore_FSASector")
   * @Template()
   */
  public function sectorAction()
  {
    $em = $this->getDoctrine()->getManager();
    $sectors = $em->getRepository('GessehCoreBundle:Sector')->findAll();

    return array(
      'sectors'       => $sectors,
      'sector_id'     => null,
      'sector_form'   => null,
    );
  }

  /**
   * Displays a form to create a new Hospital entity.
   *
   * @Route("/hospital/new", name="GCore_FSANewHospital")
   * @Template("GessehCoreBundle:FieldSetAdmin:hospitalForm.html.twig")
   */
  public function newHospitalAction()
  {
    $em = $this->getDoctrine()->getManager();
    $limit = $this->get('request')->query->get('limit', null);
    $periods = $em->getRepository('GessehCoreBundle:Period')->findAll();

    $hospital = new Hospital();
    $form   = $this->createForm(new HospitalType(), $hospital);
    $formHandler = new HospitalHandler($form, $this->get('request'), $em, $periods);

    if ( $formHandler->process() ) {
      $this->get('session')->getFlashBag()->add('notice', 'Hôpital "' . $hospital->getName() . '" enregistré.');

      return $this->redirect($this->generateUrl('GCore_FSIndex'));
    }

    return array(
        'hospital_form' => $form->createView(),
        'limit'         => $limit,
    );
  }

  /**
   * Displays a form to edit an existing Hospital entity.
   *
   * @Route("/hospital/{id}/edit", name="GCore_FSAEditHospital", requirements={"id" = "\d+"})
   * @Template("GessehCoreBundle:FieldSetAdmin:hospitalForm.html.twig")
   */
  public function editHospitalAction($id)
  {
    $em = $this->getDoctrine()->getManager();
    $limit = $this->get('request')->query->get('limit', null);
    $periods = $em->getRepository('GessehCoreBundle:Period')->findAll();

    $hospital = $em->getRepository('GessehCoreBundle:Hospital')->find($id);

    if (!$hospital)
      throw $this->createNotFoundException('Unable to find Hospital entity.');

    $editForm = $this->createForm(new HospitalType(), $hospital);
    $formHandler = new HospitalHandler($editForm, $this->get('request'), $em, $periods);

    if ( $formHandler->process() ) {
      $this->get('session')->getFlashBag()->add('notice', 'Hôpital "' . $hospital->getName() . '" modifié.');

      return $this->redirect($this->generateUrl('GCore_FSIndex'));
    }

    return array(
        'hospital_form' => $editForm->createView(),
        'limit'         => $limit,
    );
  }

  /**
   * Deletes a Hospital entity.
   *
   * @Route("/hospital/{id}/delete", name="GCore_FSADeleteHospital", requirements={"id" = "\d+"}))
   */
  public function deleteHospitalAction($id)
  {
    $em = $this->getDoctrine()->getManager();
    $hospital = $em->getRepository('GessehCoreBundle:Hospital')->find($id);
    $limit = $this->get('request')->query->get('limit', null);

    if (!$hospital)
      throw $this->createNotFoundException('Unable to find Hospital entity.');

    $em->remove($hospital);
    $em->flush();

    $this->get('session')->getFlashBag()->add('notice', 'Hôpital "' . $hospital->getName() . '" supprimé.');

    return $this->redirect($this->generateUrl('GCore_FSAIndex', array('limit' => $limit)));
  }

  /**
   * Deletes a Department entity.
   *
   * @Route("/department/{id}/delete", name="GCore_FSADeleteDepartment", requirements={"id" = "\d+"}))
   */
  public function deleteDepartmentAction($id)
  {
    $em = $this->getDoctrine()->getManager();
    $department = $em->getRepository('GessehCoreBundle:Department')->find($id);
    $limit = $this->get('request')->query->get('limit', null);

    if (!$department)
      throw $this->createNotFoundException('Unable to find Department entity.');

    $em->remove($department);
    $em->flush();

    $this->get('session')->getFlashBag()->add('notice', 'Service "' . $department->getName() . '" supprimé.');

    return $this->redirect($this->generateUrl('GCore_FSIndex', array('limit' => $limit)));
  }

  /**
   * Displays a form to create a new Sector entity.
   *
   * @Route("/sector/new", name="GCore_FSANewSector")
   * @Template("GessehCoreBundle:FieldSetAdmin:sector.html.twig")
   */
  public function newSectorAction()
  {
    $em = $this->getDoctrine()->getManager();
    $sectors = $em->getRepository('GessehCoreBundle:Sector')->findAll();

    $sector = new Sector();

    $editForm = $this->createForm(new SectorType(), $sector);
    $formHandler = new SectorHandler($editForm, $this->get('request'), $em);

    if ( $formHandler->process() ) {
      $this->get('session')->getFlashBag()->add('notice', 'Catégorie "' . $sector->getName() . '" enregistrée.');

      return $this->redirect($this->generateUrl('GCore_FSASector'));
    }

    return array (
      'sectors'       => $sectors,
      'sector_id'     => null,
      'sector_form'   => $editForm->createView(),
    );
  }

  /**
   * Displays a form to edit an existing Sector entity.
   *
   * @Route("/sector/{id}/edit", name="GCore_FSAEditSector", requirements={"id" = "\d+"})
   * @Template("GessehCoreBundle:FieldSetAdmin:sector.html.twig")
   */
  public function editSectorAction($id)
  {
    $em = $this->getDoctrine()->getManager();
    $sectors = $em->getRepository('GessehCoreBundle:Sector')->findAll();

    $sector = $em->getRepository('GessehCoreBundle:Sector')->find($id);

    if (!$sector)
        throw $this->createNotFoundException('Unable to find Sector entity.');

    $editForm = $this->createForm(new SectorType(), $sector);
    $formHandler = new SectorHandler($editForm, $this->get('request'), $em);

    if ( $formHandler->process() ) {
      $this->get('session')->getFlashBag()->add('notice', 'Catégorie "' . $sector->getName() . '" modifiée.');

      return $this->redirect($this->generateUrl('GCore_FSASector'));
    }

    return array (
      'sectors'       => $sectors,
      'sector_id'     => $id,
      'sector_form'   => $editForm->createView(),
    );
  }

  /**
   * Deletes a Sector entity.
   *
   * @Route("/sector/{id}/delete", name="GCore_FSADeleteSector")
   */
  public function deleteSectorAction($id)
  {
    $em = $this->getDoctrine()->getManager();
    $sector = $em->getRepository('GessehCoreBundle:Sector')->find($id);

    if (!$sector)
      throw $this->createNotFoundException('Unable to find Sector entity.');

    $em->remove($sector);
    $em->flush();

    $this->get('session')->getFlashBag()->add('notice', 'Catégorie "' . $sector->getName() . '" supprimée.');

    return $this->redirect($this->generateUrl('GCore_FSASector'));
  }

  /**
   * Edit the description of the Department entity.
   *
   * @Route("/department/{id}", name="GCore_FSAEditDepartmentDescription", requirements={"id" = "\d+"})
   * @Template("GessehCoreBundle:FieldSetAdmin:editDescription.html.twig")
   */
  public function editDepartmentDescriptionAction($id)
  {
    $em = $this->getDoctrine()->getManager();
    $department = $em->getRepository('GessehCoreBundle:Department')->find($id);
    $limit = $this->get('request')->query->get('limit', null);

    if (!$department)
      throw $this->createNotFoundException('Unable to find Department entity.');

    $editForm = $this->createForm(new DepartmentDescriptionType(), $department);
    $formHandler = new DepartmentHandler($editForm, $this->get('request'), $em);

    if ( $formHandler->process() ) {
      $this->get('session')->getFlashBag()->add('notice', 'Description du service "' . $department->getName() . '" enregistrée.');

      return $this->redirect($this->generateUrl('GCore_FSAEditDepartmentDescription', array('id' => $id, 'limit' => $limit)));
    }

    return array(
      'entity'    => $department,
      'edit_form' => $editForm->createView(),
      'limit'     => $limit,
    );
  }
  /**
   * Edit the description of the Hospital entity.
   *
   * @Route("/hospital/{id}", name="GCore_FSAEditHospitalDescription", requirements={"id" = "\d+"})
   * @Template("GessehCoreBundle:FieldSetAdmin:editDescription.html.twig")
   */
  public function editHospitalDescriptionAction($id)
  {
    $em = $this->getDoctrine()->getManager();
    $hospital = $em->getRepository('GessehCoreBundle:Hospital')->find($id);
    $limit = $this->get('request')->query->get('limit', null);
    $periods = $em->getRepository('GessehCoreBundle:Period')->findAll();

    if (!$hospital)
      throw $this->createNotFoundException('Unable to find Hospital entity.');

    $editForm = $this->createForm(new HospitalDescriptionType(), $hospital);
    $formHandler = new HospitalHandler($editForm, $this->get('request'), $em, $periods);

    if ( $formHandler->process() ) {
      $this->get('session')->getFlashBag()->add('notice', 'Description de l\'hôpital "' . $hospital->getName() . '" enregistrée.');

      return $this->redirect($this->generateUrl('GCore_FSAEditHospitalDescription', array('id' => $id, 'limit' => $limit)));
    }

    return array(
      'entity'    => $hospital,
      'edit_form' => $editForm->createView(),
      'limit'     => $limit,
    );
  }

  /**
   * Displays a form to add a new Accreditation entity.
   *
   * @Route("/accreditation/{id}/new", name="GCore_FSANewAccreditation", requirements={"id" = "\d+"})
   * @Template("GessehCoreBundle:FieldSetAdmin:accreditationForm.html.twig")
   */
  public function newAccreditationAction($id)
  {
    $em = $this->getDoctrine()->getManager();
    $limit = $this->get('request')->query->get('limit', null);
    $department = $em->getRepository('GessehCoreBundle:Department')->find($id);

    if (!$department)
      throw $this->createNotFoundException('Unable to find Department entity.');

    $accreditation = new Accreditation();
    $form = $this->createForm(new AccreditationType(), $accreditation);
    $formHandler = new AccreditationHandler($form, $this->get('request'), $em, $department);

    if($formHandler->process()) {
      $this->get('session')->getFlashBag()->add('notice', 'Agrément "' . $accreditation->getSector()->getName() . '" modifié.');

    return $this->redirect($this->generateUrl('GCore_FSShowDepartment', array(
      'id'    => $department->getId(),
      'limit' => $limit,
    )));
  }

    return array(
        'department_id' => $department->getId(),
        'accreditation' => null,
        'form'          => $form->createView(),
        'limit'         => $limit,
    );
  }

  /**
   * Displays a form to edit an existing Accreditation entity.
   *
   * @Route("/accreditation/{id}/edit", name="GCore_FSAEditAccreditation", requirements={"id" = "\d+"})
   * @Template("GessehCoreBundle:FieldSetAdmin:accreditationForm.html.twig")
   */
  public function editAccreditationAction($id)
  {
    $em = $this->getDoctrine()->getManager();
    $limit = $this->get('request')->query->get('limit', null);

    $accreditation = $em->getRepository('GessehCoreBundle:Accreditation')->find($id);

    if (!$accreditation)
      throw $this->createNotFoundException('Unable to find Accreditation entity.');

    $form = $this->createForm(new AccreditationType(), $accreditation);
    $formHandler = new AccreditationHandler($form, $this->get('request'), $em);

    if($formHandler->process()) {
      $this->get('session')->getFlashBag()->add('notice', 'Agrément "' . $accreditation->getSector()->getName() . '" modifié.');

      return $this->redirect($this->generateUrl('GCore_FSShowDepartment', array(
        'id'    => $accreditation->getDepartment()->getId(),
        'limit' => $limit,
      )));
    }

    return array(
        'department_id' => $accreditation->getDepartment()->getId(),
        'accreditation' => $accreditation,
        'form'          => $form->createView(),
        'limit'         => $limit,
    );
  }

  /**
   * Deletes a Accreditation entity.
   *
   * @Route("/accreditation/{id}/delete", name="GCore_FSADeleteAccreditation", requirements={"id" = "\d+"}))
   */
  public function deleteAccreditationAction($id)
  {
    $em = $this->getDoctrine()->getManager();
    $accreditation = $em->getRepository('GessehCoreBundle:Accreditation')->find($id);
    $limit = $this->get('request')->query->get('limit', null);

    if (!$accreditation)
        throw $this->createNotFoundException('Unable to find Accreditation entity.');
    $department_id = $accreditation->getDepartment()->getId();

    $em->remove($accreditation);
    $em->flush();

    $this->get('session')->getFlashBag()->add('notice', 'Agrément "' . $accreditation->getSector()->getName() . '" supprimé.');

    return $this->redirect($this->generateUrl('GCore_FSShowDepartment', array(
        'id'    => $department_id,
        'limit' => $limit,
    )));
  }

}
