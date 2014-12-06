<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <caragk@angrand.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Gesseh\UserBundle\Entity\Student;
use Gesseh\UserBundle\Form\StudentType;
use Gesseh\UserBundle\Form\StudentHandler;
use Gesseh\UserBundle\Entity\User;
use Symfony\Component\Validator\Constraints\File;

/**
 * StudentAdmin controller.
 *
 * @Route("/admin/student")
 */
class StudentAdminController extends Controller
{
  /**
   * Affiche la liste des student
   *
   * @Route("/", name="GUser_SAIndex")
   * @Template()
   */
  public function indexAction()
  {
    $em = $this->getDoctrine()->getManager();
    $search = $this->get('request')->query->get('search', null);
    $paginator = $this->get('knp_paginator');
    $students_query = $em->getRepository('GessehUserBundle:Student')->getAll($search);
    $students_count = $em->getRepository('GessehUserBundle:Student')->countAll(true, $search);
    $students = $paginator->paginate( $students_query, $this->get('request')->query->get('page', 1), 20);

    return array(
      'students'       => $students,
      'students_count' => $students_count,
      'search'         => $search,
    );
  }

  /**
   * Affiche un formulaire d'ajout d'un nouveau student
   *
   * @Route("/new", name="GUser_SANew")
   * @Template("GessehUserBundle:StudentAdmin:edit.html.twig")
   */
  public function newAction()
  {
    $em = $this->getDoctrine()->getManager();
    $manager = $this->container->get('kdb_parameters.manager');
    $mod_simul = $manager->findParamByName('simul_active');

    $student = new Student();
    $form = $this->createForm(new StudentType($mod_simul->getValue()), $student);
    $formHandler = new StudentHandler($form, $this->get('request'), $em, $this->container->get('fos_user.user_manager'));

    if ( $formHandler->process() ) {
      $this->get('session')->getFlashBag()->add('notice', 'Étudiant "' . $student . '" enregistré.');

      return $this->redirect($this->generateUrl('GUser_SAIndex'));
    }

    return array(
      'student'      => null,
      'student_form' => $form->createView(),
    );
  }

  /**
   * Affiche un formulaire de modification de student
   *
   * @Route("/{id}/edit", name="GUser_SAEdit", requirements={"id" = "\d+"})
   * @Template("GessehUserBundle:StudentAdmin:edit.html.twig")
   */
  public function editAction($id)
  {
    $em = $this->getDoctrine()->getManager();
    $manager = $this->container->get('kdb_parameters.manager');
    $mod_simul = $manager->findParamByName('simul_active');

    $student = $em->getRepository('GessehUserBundle:Student')->find($id);

    if( !$student )
      throw $this->createNotFoundException('Unable to find Student entity.');

    $form = $this->createForm(new StudentType($mod_simul->getValue()), $student);
    $formHandler = new StudentHandler($form, $this->get('request'), $em, $this->container->get('fos_user.user_manager'));

    if ( $formHandler->process() ) {
      $this->get('session')->getFlashBag()->add('notice', 'Étudiant "' . $student . '" modifié.');

      return $this->redirect($this->generateUrl('GUser_SAIndex'));
    }

    return array(
      'student'      => $student,
      'student_form' => $form->createView(),
    );
  }

  /**
   * @Route("/{id}/delete", name="GUser_SADelete", requirements={"id" = "\d+"})
   */
  public function deleteAction($id)
  {
    $em = $this->getDoctrine()->getManager();
    $student = $em->getRepository('GessehUserBundle:Student')->find($id);

    if( !$student )
      throw $this->createNotFoundException('Unable to find Student entity.');

    $em->remove($student);
    $em->flush();

    $this->get('session')->getFlashBag()->add('notice', 'Etudiant "' . $student . '" supprimé.');

    return $this->redirect($this->generateUrl('GUser_SAIndex'));
  }

  /**
   * @Route("/{id}/promote", name="GUser_SAPromote", requirements={"id" = "\d+"})
   */
  public function promoteAction($id)
  {
    $em = $this->getDoctrine()->getManager();
    $um = $this->container->get('fos_user.user_manager');
    $student = $em->getRepository('GessehUserBundle:Student')->find($id);

    if( !$student )
      throw $this->createNotFoundException('Unable to find Student entity.');

    $user = $student->getUser();
    $user->addRole('ROLE_ADMIN');

    $um->updateUser($user);

    $this->get('session')->getFlashBag()->add('notice', 'Droits d\'administration donnés à l\'étudiant "' . $student . '"');

    return $this->redirect($this->generateUrl('GUser_SAIndex'));
  }

  /**
   * @Route("/{id}/demote", name="GUser_SADemote", requirements={"id" = "\d+"})
   */
  public function demoteAction($id)
  {
    $em = $this->getDoctrine()->getManager();
    $um = $this->container->get('fos_user.user_manager');
    $student = $em->getRepository('GessehUserBundle:Student')->find($id);

    if( !$student )
      throw $this->createNotFoundException('Unable to find Student entity.');

    $user = $student->getUser();
    if( $user->hasRole('ROLE_ADMIN') )
      $user->removeRole('ROLE_ADMIN');
    $um->updateUser($user);

    $this->get('session')->getFlashBag()->add('notice', 'Droits d\'administration retirés à l\'étudiant "' . $student . '"');

    return $this->redirect($this->generateUrl('GUser_SAIndex'));
  }

  /**
   * Import students from file into a grade
   *
   * @Route("/import", name="GUser_SAImport")
   * @Template()
   */
  public function importAction(Request $request)
  {
    $em = $this->getDoctrine()->getManager();
    $um = $this->container->get('fos_user.user_manager');
    $error = null;
    $choices = array(
        '0'  => '1re colonne',
        '1'  => '2e colonne',
        '2'  => '3e colonne',
        '3'  => '4e colonne',
        '4'  => '5e colonne',
        '5'  => '6e colonne',
        '6'  => '7e colonne',
        '7'  => '8e colonne',
        '8'  => '9e colonne',
        '9'  => '10e colonne',
        '10' => '11e colonne',
        '11' => '12e colonne',
        '12' => '13e colonne',
        '13' => '14e colonne',
        '14' => '15e colonne',
        '15' => '16e colonne',
    );

    $form = $this->createFormBuilder()
        ->add('file', 'file', array(
            'label'    => 'Fichier',
            'required' => true,
        ))
        ->add('surname', 'choice', array(
            'label'    => 'Nom',
            'required' => true,
            'expanded' => false,
            'multiple' => false,
            'choices'  => $choices,
        ))
        ->add('name', 'choice', array(
            'label'    => 'Prénom',
            'required' => true,
            'expanded' => false,
            'multiple' => false,
            'choices'  => $choices,
        ))
        ->add('phone', 'choice', array(
            'label'    => 'Téléphone',
            'required' => false,
            'expanded' => false,
            'multiple' => false,
            'choices'  => $choices,
            'empty_value' => 'aucune',
            'empty_data'  => null,
        ))
        ->add('address', 'choice', array(
            'label'    => 'Adresse',
            'required' => false,
            'expanded' => false,
            'multiple' => false,
            'choices'  => $choices,
            'empty_value' => 'aucune',
            'empty_data'  => null,
        ))
        ->add('email', 'choice', array(
            'label'    => 'E-mail',
            'required' => true,
            'expanded' => false,
            'multiple' => false,
            'choices'  => $choices,
        ))
        ->add('ranking', 'choice', array(
            'label'    => 'Classement ECN',
            'required' => false,
            'expanded' => false,
            'multiple' => false,
            'choices'  => $choices,
            'empty_value' => 'aucune',
            'empty_data'  => null,
        ))
        ->add('graduate', 'choice', array(
            'label'    => 'Année ECN',
            'required' => false,
            'expanded' => false,
            'multiple' => false,
            'choices'  => $choices,
            'empty_value' => 'aucune',
            'empty_data'  => null,
        ))
        ->add('grade', 'entity', array(
            'label'    => 'Promotion',
            'required' => true,
            'class'    => 'GessehUserBundle:Grade',
        ))
        ->add('Envoyer', 'submit')
        ->getForm();

    $form->handleRequest($request);

    if ($form->isValid()) {
        $fileConstraint = new File();
        $fileConstraint->mimeTypesMessage = "Invalid mime type : ODS or XLS required.";
        $fileConstraint->mimeTypes = array(
            'application/vnd.oasis.opendocument.spreadsheet',
            'application/vnd.ms-excel',
        );
        $errorList = $this->get('validator')->validateValue($form['file']->getData(), $fileConstraint);

        if(count($errorList) == 0) {

        $objPHPExcel = $this->get('phpexcel')->createPHPExcelObject($form['file']->getData())->setActiveSheetIndex();
        $students_count = 2;

        while ($objPHPExcel->getCellByColumnAndRow($form['surname']->getData(), $students_count)->getValue()) {
            $student = new Student();
            $student->setSurname($objPHPExcel->getCellByColumnAndRow($form['surname']->getData(), $students_count)->getValue());
            $student->setName($objPHPExcel->getCellByColumnAndRow($form['name']->getData(), $students_count)->getValue());
            if ($form['phone']->getData() != null)
                $student->setPhone($objPHPExcel->getCellByColumnAndRow($form['phone']->getData(), $students_count)->getValue());
            if ($form['address']->getData() != null)
                $student->setAddress($objPHPExcel->getCellByColumnAndRow($form['address']->getData(), $students_count)->getValue());
            if ($form['ranking']->getData() != null)
                $student->setRanking($objPHPExcel->getCellByColumnAndRow($form['ranking']->getData(), $students_count)->getValue());
            if ($form['graduate']->getData() != null)
                $student->setGraduate($objPHPExcel->getCellByColumnAndRow($form['graduate']->getData(), $students_count)->getValue());
            $student->setAnonymous(false);
            $student->setGrade($form['grade']->getData());
            $user = new User();
            $um->createUser();
            $user->setEmail($objPHPExcel->getCellByColumnAndRow($form['email']->getData(), $students_count)->getValue());
            $user->setUsername($user->getEmail());
            $user->setConfirmationToken(null);
            $user->setEnabled(true);
            $user->addRole('ROLE_STUDENT');
            $user->setPlainPassword('tatatatata');
            $student->setUser($user);
            $em->persist($student);
            $um->updateUser($user);
            $students_count++;
        }

        $em->flush();

        $this->get('session')->getFlashBag()->add('notice', $students_count - 2 . " ont été enregistrés dans la base de données.");

        return $this->redirect($this->generateUrl('GUser_SAIndex'));
        } else {
            $error = $errorList[0]->getMessage();
        }
    }

    return array(
        'form'  => $form->createView(),
        'error' => $error,
    );
  }

    /**
     * Exporter les adresses mail des étudiants par promotion
     *
     * @Route("/export/{grade_id}/mail", name="GUser_SAExportMail", requirements={"grade_id" = "\d+"})
     * @Template()
     */
    public function exportMailAction($grade_id)
    {
        $em = $this->getDoctrine()->getManager();
        $mails = $em->getRepository('GessehUserBundle:Student')->getMailsByGrade($grade_id);
        $grade = $em->getRepository('GessehUserBundle:Grade')->find($grade_id);

        return array(
            'mails'  => $mails,
            'grade' => $grade,
        );
    }

}
