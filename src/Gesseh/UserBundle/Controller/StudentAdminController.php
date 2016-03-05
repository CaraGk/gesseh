<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2013-2016 Pierre-François Angrand
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
        $pm = $this->container->get('kdb_parameters.manager');
        $um = $this->container->get('fos_user.user_manager');
        $username = $this->get('security.token_storage')->getToken()->getUsername();
        $user = $um->findUserByUsername($username);
        $search = $this->get('request')->query->get('search', null);
        $paginator = $this->get('knp_paginator');
        $students_query = $em->getRepository('GessehUserBundle:Student')->getAll($search);
        $students_count = $em->getRepository('GessehUserBundle:Student')->countAll(true, $search);
        $students = $paginator->paginate( $students_query, $this->get('request')->query->get('page', 1), 20);

        $member_list = null;
        if ($pm->findParamByName('reg_active')->getValue())
        {
            if ($user->hasRole('ROLE_ADMIN') or ($user->hasRole('ROLE_SUPERTEACHER') and $pm->findParamByName('reg_teacher_access')->getValue())) {
                foreach ($members = $em->getRepository('GessehRegisterBundle:Membership')->getCurrentForStudentArray() as $member) {
                    $member_list[] = $member['id'];
                }
            }
        }

    return array(
      'students'       => $students,
      'students_count' => $students_count,
      'search'         => $search,
      'members'        => $member_list,
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
      $pm = $this->container->get('kdb_parameters.manager');
    $search = $this->get('request')->query->get('search', null);
    $student = $em->getRepository('GessehUserBundle:Student')->find($id);

    if( !$student )
        throw $this->createNotFoundException('Unable to find Student entity.');

    if(true == $pm->findParamByName('reg_active')->getValue()) {
        if($memberships = $em->getRepository('GessehRegisterBundle:Membership')->findBy(array('student' => $student))) {
            foreach($memberships as $membership) {
                $em->remove($membership);
            }
        }
    }

    $em->remove($student);
    $em->flush();

    $this->get('session')->getFlashBag()->add('notice', 'Etudiant "' . $student . '" supprimé.');

    return $this->redirect($this->generateUrl('GUser_SAIndex', array('search' => $search)));
  }

  /**
   * @Route("/{id}/promote", name="GUser_SAPromote", requirements={"id" = "\d+"})
   */
  public function promoteAction($id)
  {
    $em = $this->getDoctrine()->getManager();
    $um = $this->container->get('fos_user.user_manager');
    $search = $this->get('request')->query->get('search', null);
    $student = $em->getRepository('GessehUserBundle:Student')->find($id);

    if( !$student )
      throw $this->createNotFoundException('Unable to find Student entity.');

    $user = $student->getUser();
    $user->addRole('ROLE_ADMIN');

    $um->updateUser($user);

    $this->get('session')->getFlashBag()->add('notice', 'Droits d\'administration donnés à l\'étudiant "' . $student . '"');

    return $this->redirect($this->generateUrl('GUser_SAIndex', array('search' => $search)));
  }

  /**
   * @Route("/{id}/demote", name="GUser_SADemote", requirements={"id" = "\d+"})
   */
  public function demoteAction($id)
  {
    $em = $this->getDoctrine()->getManager();
    $um = $this->container->get('fos_user.user_manager');
    $search = $this->get('request')->query->get('search', null);
    $student = $em->getRepository('GessehUserBundle:Student')->find($id);

    if( !$student )
      throw $this->createNotFoundException('Unable to find Student entity.');

    $user = $student->getUser();
    if( $user->hasRole('ROLE_ADMIN') )
      $user->removeRole('ROLE_ADMIN');
    $um->updateUser($user);

    $this->get('session')->getFlashBag()->add('notice', 'Droits d\'administration retirés à l\'étudiant "' . $student . '"');

    return $this->redirect($this->generateUrl('GUser_SAIndex', array('search' => $search)));
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
    $listUsers = $em->getRepository('GessehUserBundle:User')->getAllEmail();
    $choices = array(
        '0'  => '1re colonne (A)',
        '1'  => '2e colonne (B)',
        '2'  => '3e colonne (C)',
        '3'  => '4e colonne (D)',
        '4'  => '5e colonne (E)',
        '5'  => '6e colonne (F)',
        '6'  => '7e colonne (G)',
        '7'  => '8e colonne (H)',
        '8'  => '9e colonne (I)',
        '9'  => '10e colonne (J)',
        '10' => '11e colonne (K)',
        '11' => '12e colonne (L)',
        '12' => '13e colonne (M)',
        '13' => '14e colonne (N)',
        '14' => '15e colonne (O)',
        '15' => '16e colonne (P)',
        '16' => '17e colonne (Q)',
        '17' => '18e colonne (R)',
        '18' => '19e colonne (S)',
        '19' => '20e colonne (T)',
        '20' => '21e colonne (U)',
        '21' => '22e colonne (V)',
    );

    $form = $this->createFormBuilder()
        ->add('file', 'file', array(
            'label'    => 'Fichier',
            'required' => true,
        ))
        ->add('first_row', 'checkbox', array(
            'label'    => 'Le fichier contient une ligne de titre de colonnes',
            'required' => false,
            'data'     => true,
        ))
        ->add('title', 'choice', array(
            'label' => 'Titre',
            'required' => false,
            'expanded' => false,
            'multiple' => false,
            'choices'  => $choices,
            'placeholder' => 'aucune',
            'empty_data'  => null,
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
        ->add('email', 'choice', array(
            'label'    => 'E-mail',
            'required' => true,
            'expanded' => false,
            'multiple' => false,
            'choices'  => $choices,
        ))
        ->add('birthday', 'choice', array(
            'label'    => 'Date de naissance',
            'required' => false,
            'expanded' => false,
            'multiple' => false,
            'choices'  => $choices,
            'placeholder' => 'aucune',
            'empty_data'  => null,
        ))
        ->add('birthplace', 'choice', array(
            'label'    => 'Lieu de naissance',
            'required' => false,
            'expanded' => false,
            'multiple' => false,
            'choices'  => $choices,
            'placeholder' => 'aucune',
            'empty_data'  => null,
        ))
        ->add('phone', 'choice', array(
            'label'    => 'Téléphone',
            'required' => false,
            'expanded' => false,
            'multiple' => false,
            'choices'  => $choices,
            'placeholder' => 'aucune',
            'empty_data'  => null,
        ))
        ->add('ranking', 'choice', array(
            'label'    => 'Classement ECN',
            'required' => false,
            'expanded' => false,
            'multiple' => false,
            'choices'  => $choices,
            'placeholder' => 'aucune',
            'empty_data'  => null,
        ))
        ->add('graduate', 'choice', array(
            'label'    => 'Année ECN',
            'required' => false,
            'expanded' => false,
            'multiple' => false,
            'choices'  => $choices,
            'placeholder' => 'aucune',
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
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/octet-stream',
        );
        $errorList = $this->get('validator')->validateValue($form['file']->getData(), $fileConstraint);

        if(count($errorList) == 0) {

            $objPHPExcel = $this->get('phpexcel')->createPHPExcelObject($form['file']->getData())->setActiveSheetIndex();
            if ($form['first_row']->getData() == true)
                $first_row = 2;
            else
                $first_row = 1;
            $students_count = $first_row;
            $students_error = 0;
            $newUsers = array();

            while ($objPHPExcel->getCellByColumnAndRow($form['surname']->getData(), $students_count)->getValue()) {
                $student = new Student();
                if ($form['title']->getData() != null)
                   $student->setTitle($objPHPExcel->getCellByColumnAndRow($form['title']->getData(), $students_count)->getValue());
                $student->setSurname($objPHPExcel->getCellByColumnAndRow($form['surname']->getData(), $students_count)->getValue());
                $student->setName($objPHPExcel->getCellByColumnAndRow($form['name']->getData(), $students_count)->getValue());
                if ($form['birthday']->getData() != null) {
                    $birthday = new \DateTime($objPHPExcel->getCellByColumnAndRow($form['birthday']->getData(), $students_count)->getValue());
                    $student->setBirthday($birthday);
                }
                if ($form['birthplace']->getData() != null)
                    $student->setBirthplace($objPHPExcel->getCellByColumnAndRow($form['birthplace']->getData(), $students_count)->getValue());
                if ($form['phone']->getData() != null)
                    $student->setPhone($objPHPExcel->getCellByColumnAndRow($form['phone']->getData(), $students_count)->getValue());
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

                if (!(in_array(array("emailCanonical" => $user->getEmail()), $listUsers) || in_array($user->getEmail(), $newUsers))) {
                    $em->persist($student);
                    $um->updateUser($user);
                    $newUsers[] = $user->getEmail();
                } else {
                    $this->get('session')->getFlashBag()->add('error', $student->getName() . ' ' . $student->getSurname() . ' (' . $student->getUser()->getEmail() . ') : l\'utilisateur existe déjà dans la base de données.');
                    $students_error++;
                }
                $students_count++;
            }

            $em->flush();

            if ($students_count - $first_row > 1) {
                $message = $students_count - $first_row . " lignes ont été traitées. ";
            } elseif ($students_count - $first_row == 1) {
                $message = "Une ligne a été traitée. ";
            } else {
                $message = "Aucune ligne n'a été traitée.";
            }
            if ($students_error) {
                if ($students_count - $first_row - $students_error > 1) {
                    $message .= $students_count - $first_row - $students_error . " étudiants ont été enregistrés dans la base de données. ";
                } elseif ($students_count - $first_row - $students_error == 1) {
                    $message .= "Un étudiant a été enregistré dans la base de données. ";
                } else {
                    $message .= "Aucun étudiant n'a été enregistré dans la base de données. ";
                }
                if ($students_error > 1) {
                    $message .= $students_error . " doublons n'ont pas été ajoutés.";
                } else {
                    $message .= "Un doublon n'a pas été ajouté.";
                }
            } else {
                $message .= $students_count - $first_row . " étudiants ont été enregistrés dans la base de données. Il n'y a pas de doublons traités.";
            }
            $this->get('session')->getFlashBag()->add('notice', $message);

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
