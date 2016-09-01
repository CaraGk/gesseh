<?php

namespace Gesseh\ImportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\DiExtraBundle\Annotation as DI;
use JMS\SecurityExtraBundle\Annotation as Security;
use Symfony\Component\Finder\Finder;
use Gesseh\UserBundle\Entity\Student;
use Gesseh\UserBundle\Entity\User;
use Gesseh\EvaluationBundle\Entity\Evaluation;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * Import controller
 *
 * Ce bundle est un exemple de possibilités d'import d'évaluations depuis un
 * tableur, conçu initialement pour la migration des données d'EvalTonStage, le
 * site de l'AIMGL.
 *
 * @Route("/admin/import")
 * @Security\Secure(roles="ROLE_ADMIN")
 */

class DefaultController extends Controller
{
    /** @DI\Inject */
    private $request;

    /** @DI\Inject */
    private $router;

    /** @DI\Inject("doctrine.orm.entity_manager") */
    private $em;

    /** @DI\Inject("fos_user.user_manager") */
    private $um;

    /**
     * @Route("/prepare/hospital")
     */
    public function prepareStudentAndFieldsetForHospitalAction()
    {
        $object = $this->get('phpexcel')->createPHPExcelObject(__DIR__.'/../Resources/data/evals.xls');
        $error['row'] = 0;
        $worksheet = $object->setActiveSheetIndex(1);
        $rows = $worksheet->getHighestRow();
        $row = 2;

        for ($worksheet->getCellByColumnAndRow(0, $row)->getValue() ; $row <= $rows ; ++$row) {
            $lastname = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
            $secondname = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
            $firstname = $worksheet->getCellByColumnAndRow(4, $row)->getValue();

            $student = $this->em->getRepository('GessehUserBundle:Student')->findOneBy(array('surname' => $lastname, 'name' => $firstname));

            if (count($student) > 1) {
                $max = 0;
                $topStud = null;
                foreach ($student as $key => $current) {
                    if ($count = count($current->getPlacements()) > $max) {
                        $max = $count;
                        $topStud = $current;
                    }
                }
                if ($max == 0)
                    $topStud = $student[0];

                $worksheet->getCellByColumnAndRow(0, $row)->setValue($topStud->getId());
            } elseif ($student) {
                $worksheet->getCellByColumnAndRow(0, $row)->setValue($student->getId());
            } else {
                $worksheet->getCellByColumnAndRow(0, $row)->setValue();
            }

            $hospital_title = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
            $department_title = strtolower($worksheet->getCellByColumnAndRow(9, $row)->getValue());

            $department = $this->em->getRepository('GessehCoreBundle:Department')->getByNameAndHospital($department_title, $hospital_title);
            if ($department) {
                $worksheet->getCellByColumnAndRow(8, $row)->setValue($department->getId());
            } else {
                $worksheet->getCellByColumnAndRow(8, $row)->setValue();
            }
        }

        $writer = $this->get('phpexcel')->createWriter($object, 'Excel5');
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'eval_data.xls'
        );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;
    }

    /**
     * @Route("/prepare/ambu")
     */
    public function prepareStudentAndFieldsetForAmbuAction()
    {
        $object = $this->get('phpexcel')->createPHPExcelObject(__DIR__.'/../Resources/data/eval_data.xls');
        $error['row'] = 0;
        $worksheet = $object->setActiveSheetIndex(0);
        $rows = $worksheet->getHighestRow();
        $row = 2;

        for ($worksheet->getCellByColumnAndRow(0, $row)->getValue() ; $row <= $rows ; ++$row) {
            $lastname = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
            $secondname = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
            $firstname = $worksheet->getCellByColumnAndRow(4, $row)->getValue();

            $student = $this->em->getRepository('GessehUserBundle:Student')->findOneBy(array('surname' => $lastname, 'name' => $firstname));

            if (count($student) > 1) {
                $max = 0;
                $topStud = null;
                foreach ($student as $key => $current) {
                    if ($count = count($current->getPlacements()) > $max) {
                        $max = $count;
                        $topStud = $current;
                    }
                }
                if ($max == 0)
                    $topStud = $student[0];

                $worksheet->getCellByColumnAndRow(0, $row)->setValue($topStud->getId());
            } elseif ($student) {
                $worksheet->getCellByColumnAndRow(0, $row)->setValue($student->getId());
            } else {
                $worksheet->getCellByColumnAndRow(0, $row)->setValue();
            }

            $hospital_title = "Stage Ambulatoire";
            $department_title = strtolower($worksheet->getCellByColumnAndRow(8, $row)->getValue() . ' ' . $worksheet->getCellByColumnAndRow(9, $row)->getValue() . ' ' . $worksheet->getCellByColumnAndRow(10, $row)->getValue());

            $department = $this->em->getRepository('GessehCoreBundle:Department')->getByNameAndHospital($department_title, $hospital_title);
            if ($department) {
                $worksheet->getCellByColumnAndRow(7, $row)->setValue($department->getId());
            } else {
                $worksheet->getCellByColumnAndRow(7, $row)->setValue();
            }
        }

        $writer = $this->get('phpexcel')->createWriter($object, 'Excel5');
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'eval_data_1.xls'
        );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;
    }

    /**
     * @Route("/prepare/placement/hospital")
     */
    public function preparePlacementForHospitalAction()
    {
        $object = $this->get('phpexcel')->createPHPExcelObject(__DIR__.'/../Resources/data/eval_data_1.xls');
        $error['row'] = 0;
        $worksheet = $object->setActiveSheetIndex(1);
        $rows = $worksheet->getHighestRow();
        $row = 2;

        for ($worksheet->getCellByColumnAndRow(0, $row)->getValue() ; $row <= $rows ; ++$row) {
            $student_id = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
            $department_id = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
            $placements = $this->em->getRepository('GessehCoreBundle:Placement')->getByStudentAndDepartment($student_id, $department_id);

            if ($placements) {
                if (count($placements) > 1) {

                    /* il faudrait choisir comment les répartir sachant que,
                     * majoritairement, l'évaluation est unique (choisir le
                     * premier placement ?) mais qu'il y a quand même 1
                     * occurence de 2 évaluations différentes (vérifier les
                     * id_stage dans le fichier original) */

                    $worksheet->getCellByColumnAndRow(10, $row)->setValue($placements[0]->getId());
                } else {
                     $worksheet->getCellByColumnAndRow(10, $row)->setValue($placements[0]->getId());
                }
                $modId[$worksheet->getCellByColumnAndRow(67, $row)->getValue()] = $placement[0]->getId();
            } else {
                 $worksheet->getCellByColumnAndRow(10, $row)->setValue();
            }
        }

        $worksheet = $object->setActiveSheetIndex(2);
        for ($row = 2, $rows = $worksheet->getHighestRow() ; $row <= $rows ; ++$row) {
            if ($worksheet->getCellByColumnAndRow(1, $row)->getValue() == 'sh') {
                if (isset($modId[$worksheet->getCellByColumnAndRow(1, $row)->getValue()])) {
                    $worksheet->getCellByColumnAndRow(0, $row)->setValue($modId[$worksheet->getCellByColumnAndRow(1, $row)->getValue()]);
                } else {
                    $worksheet->getCellByColumnAndRow(0, $row)->setValue();
                }
            }
        }

        $writer = $this->get('phpexcel')->createWriter($object, 'Excel5');
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'eval_data_2.xls'
        );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;
    }

    /**
     * @Route("/prepare/placement/ambu")
     */
    public function preparePlacementForAmbuAction()
    {
        $object = $this->get('phpexcel')->createPHPExcelObject(__DIR__.'/../Resources/data/eval_data_2.xls');
        $error['row'] = 0;
        $worksheet = $object->setActiveSheetIndex(0);
        $rows = $worksheet->getHighestRow();
        $row = 2;

        for ($worksheet->getCellByColumnAndRow(0, $row)->getValue() ; $row <= $rows ; ++$row) {
            $student_id = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
            $department_id = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
            $placements = $this->em->getRepository('GessehCoreBundle:Placement')->getByStudentAndDepartment($student_id, $department_id);

            if ($placements) {
                if (count($placements) > 1) {

                    /* il faudrait choisir comment les répartir sachant que,
                     * majoritairement, l'évaluation est unique (choisir le
                     * premier placement ?) mais qu'il y a quand même 1
                     * occurence de 2 évaluations différentes (vérifier les
                     * id_stage dans le fichier original) */

                    $worksheet->getCellByColumnAndRow(10, $row)->setValue($placements[0]->getId());
                } else {
                     $worksheet->getCellByColumnAndRow(10, $row)->setValue($placements[0]->getId());
                }
                $modId[$worksheet->getCellByColumnAndRow(6, $row)->getValue()] = $placement[0]->getId();
            } else {
                 $worksheet->getCellByColumnAndRow(10, $row)->setValue();
            }
        }

        $worksheet = $object->setActiveSheetIndex(2);
        for ($row = 2, $rows = $worksheet->getHighestRow() ; $row <= $rows ; ++$row) {
            if ($worksheet->getCellByColumnAndRow(1, $row)->getValue() == 'sa') {
                if (isset($modId[$worksheet->getCellByColumnAndRow(1, $row)->getValue()])) {
                    $worksheet->getCellByColumnAndRow(0, $row)->setValue($modId[$worksheet->getCellByColumnAndRow(1, $row)->getValue()]);
                } else {
                    $worksheet->getCellByColumnAndRow(0, $row)->setValue();
                }
            }
        }

        $writer = $this->get('phpexcel')->createWriter($object, 'Excel5');
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'eval_data_3.xls'
        );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;
    }

    /**
     * @Route("/hospital")
     */
    public function importHospitalAction()
    {
        $q = array(
            15 => array(1 => 369, 3 => 316, 4 => 263, 5 => 153, 6 => 209), //Début de stage
            17 => array(1 => 370, 3 => 317, 4 => 264, 5 => 154, 6 => 210), //Fin de stage
            19 => array(1 => 371, 3 => 316, 4 => 265, 5 => 155, 6 => 211), //Demi journées
            20 => array(1 => 372, 3 => 319, 4 => 266, 5 => 156, 6 => 212), //Samedis
            21 => array(1 => 418, 3 => 365, 4 => 312, 5 => 202, 6 => 258), //Commentaire 1
            22 => array(1 => 373, 3 => 320, 4 => 267, 5 => 157, 6 => 213, 'result' => array(0 => 0, 1 => 1, 3 => 2, 5 => 3)), //présentation équipe
            23 => array(1 => 374, 3 => 321, 4 => 268, 5 => 158, 6 => 214, 'result' => array(0 => 0, 1 => 1, 3 => 2, 5 => 3)), //organisation
            24 => array(1 => 375, 3 => 322, 4 => 269, 5 => 159, 6 => 215, 'result' => array(0 => 0, 1 => 1, 3 => 2, 5 => 3)), //objectifs
            25 => array(1 => 376, 3 => 323, 4 => 270, 5 => 160, 6 => 216, 'result' => array(0 => 1, 5 => 0)), //lieu de travail
            26 => array(1 => 378, 3 => 325, 4 => 272, 5 => 162, 6 => 218, 'result' => array(0 => 1, 4 => 0)), //internet
            27 => array(1 => 379, 3 => 326, 4 => 273, 5 => 163, 6 => 219, 'result' => array(0 => 0, 1 => 1, 2 => 2, 3 => 3)), //qualité d es locaux
            28 => array(1 => 380, 3 => 327, 4 => 274, 5 => 164, 6 => 220, 'result' => array(0 => 0, 1 => 1, 2 => 2, 3 => 3)), //ressources docs
            29 => array(1 => 381, 3 => 328, 4 => 275, 5 => 165, 6 => 221, 'result' => array(0 => 0, 5 => 1, 10 => 2)), //prise de responsabilité
            30 => array(1 => 382, 3 => 329, 4 => 276, 5 => 166, 6 => 222, 'result' => array(0 => 0, 5 => 1)), //cours
            31 => array(1 => 383, 3 => 330, 4 => 277, 5 => 167, 6 => 223), //combien
            32 => array(1 => 384, 3 => 331, 4 => 278, 5 => 168, 6 => 224, 'result' => array(0 => 1, 5 => 0)), //staff
            33 => array(1 => 385, 3 => 332, 4 => 279, 5 => 169, 6 => 225, 'result' => array(0 => 0, 3 => 1)), //portfolio
            34 => array(1 => 386, 3 => 333, 4 => 280, 5 => 170, 6 => 226, 'result' => array(0 => 0, 3 => 1, 5 => 2, 8 => 3)), //seniors
            35 => array(1 => 387, 3 => 334, 4 => 281, 5 => 171, 6 => 227, 'max' => 5), //encadrement
            37 => array(1 => 389, 3 => 336, 4 => 283, 5 => 173, 6 => 229, 'result' => array(0 => 2, 3 => 1, 5 => 0)), // pubilcations
            38 => array(1 => 390, 3 => 337, 4 => 284, 5 => 174, 6 => 230, 'result' => array(3 => 0, 4 => 1, 6 => 2)), //thèse
            39 => array(1 => 391, 3 => 338, 4 => 285, 5 => 175, 6 => 231, 'max' => 15), //formateur
            40 => array(1 => 392, 3 => 339, 4 => 286, 5 => 176, 6 => 232, 'result' => array(0 => 1, 15 => 1)), //recommandation
            41 => array(1 => 393, 3 => 340, 4 => 287, 5 => 177, 6 => 233, 'max' => 15), //medecine générale
            42 => array(1 => 394, 3 => 341, 4 => 288, 5 => 178, 6 => 234, 'max' => 10), // comp:décisions
            43 => array(1 => 395, 3 => 342, 4 => 289, 5 => 179, 6 => 235, 'max' => 10), // comp:incertitude
            44 => array(1 => 396, 3 => 343, 4 => 290, 5 => 180, 6 => 236, 'max' => 10), // comp:gestes
            45 => array(1 => 397, 3 => 344, 4 => 291, 5 => 181, 6 => 237, 'max' => 10), // comp:communiquer
            46 => array(1 => 398, 3 => 345, 4 => 292, 5 => 182, 6 => 238, 'max' => 10), // comp:éduquer
            47 => array(1 => 399, 3 => 346, 4 => 293, 5 => 183, 6 => 239, 'max' => 10), // comp:santé pubilque
            48 => array(1 => 400, 3 => 347, 4 => 294, 5 => 184, 6 => 240, 'max' => 10), // comp:équipe
            49 => array(1 => 401, 3 => 348, 4 => 295, 5 => 185, 6 => 241, 'max' => 10), // comp:suivi
            50 => array(1 => 402, 3 => 349, 4 => 296, 5 => 186, 6 => 242, 'max' => 10), // comp:éthique
            51 => array(1 => 403, 3 => 350, 4 => 297, 5 => 187, 6 => 243, 'max' => 10), // comp:gestion
            52 => array(1 => 404, 3 => 351, 4 => 298, 5 => 188, 6 => 244, 'max' => 10), // comp:formation
            56 => array(1 => 405, 3 => 352, 4 => 299, 5 => 189, 6 => 245, 'max' => 16), // charge de travail
            57 => array(1 => 406, 3 => 353, 4 => 300, 5 => 190, 6 => 246, 'result' =>  array(0 => 3, 1 => 3, 2 => 3, 3 => 3, 4 => 3, 5 => 2, 6 => 0, 7 => 2, 8 => 0, 9 => 2, 10 => 1, 11 => 1, 12 => 1, 13 => 1, 14 => 1, 15 => 1, 16 => 1)), // secrétariat
            58 => array(1 => 407, 3 => 354, 4 => 301, 5 => 191, 6 => 247, 'max' => 10), // ambiance:interne
            59 => array(1 => 408, 3 => 355, 4 => 302, 5 => 192, 6 => 248, 'max' => 10), // ambiance:médecins
            60 => array(1 => 409, 3 => 356, 4 => 303, 5 => 193, 6 => 249, 'max' => 10), // ambiance:paramed
            64 => array(1 => 419, 3 => 366, 4 => 313, 5 => 203, 6 => 259), //commentaires libres
//            65 => array(1 => 367, 3 => null, 4 => 261, 5 => 151, 6 => null), //lits
//            66 => array(1 => 368, 3 => null, 4 => 262, 5 => 152, 6 => null), //visites
//            81 => array(1 => null, 3 => null, 4 => 261, 5 => null, 6 => null), //lits péd
//            82 => array(1 => null, 3 => null, 4 => 262, 5 => null, 6 => null), //visites ped
//            83 => array(1 => null, 3 => null, 4 => null, 5 => null, 6 => 204), //fréquentation urg
//            89 => array(1 => null, 3 => null, 4 => null, 5 => null, 6 => 207), //senior présents urg
//            90 => array(1 => null, 3 => null, 4 => null, 5 => null, 6 => 208), //internes nécessaires urg
        );
        $object = $this->get('phpexcel')->createPHPExcelObject(__DIR__.'/../Resources/data/eval_data_1.xls');
        $worksheet = $object->setActiveSheetIndex(1);
        $error['row'] = '';
        $error['multiple'] = 0;
        $error['none'] = 0;
        $error['eval'] = 0;
        $error['empty'] = 0;
        $valid['eval'] = 0;
        $error['form'] = 0;
        $error['form_row'] = '';

        for ($row = 2, $rows = $worksheet->getHighestRow() ; $row <= $rows ; ++$row) {
            if ($placement_id = $worksheet->getCellByColumnAndRow(10, $row)->getValue()) {
                $placement = $this->em->getRepository('GessehCoreBundle:Placement')->find($worksheet->getCellByColumnAndRow(10, $row)->getValue());
                if ($placement) {
                    $c = array();
                    $eval_forms = $this->em->getRepository('GessehEvaluationBundle:EvalForm')->getByPlacement($placement_id);
                    if (count($eval_forms) > 1) {
                        $error['multiple']++;
                        $error['row'] .= $row . ' [';
                        foreach ($eval_forms as $form) {
                            $error['row'] .= $form->getId() . ',';
                            if($form->getId() != 2)
                                $eval_form = $form;
                        }
                        $error['row'] .= '] - ';
                    } elseif (!$eval_forms or $eval_forms[0]->getId() == 2) {
                        $error['none']++;
                        $error['row'] .= $row . ' (' . $placement->getRepartition()->getDepartment()->getName() . ' à ' . $placement->getRepartition()->getDepartment()->getHospital()->getName() . ') - ';
                        continue;
                    } else {
                         $eval_form = $eval_forms[0];
                    }
                    $eval_form_id = $eval_form->getId();
                    foreach($eval_form->getCriterias() as $criteria) {
                         $c[$criteria->getId()] = $criteria;
                    }

                    $excelDate = $worksheet->getCellByColumnAndRow(14, $row)->getValue();
                    $unixDate = ($excelDate - 25569) * 86400;
                    $date = new \DateTime();
                    $date->setTimestamp($unixDate);

                    for ($i = 15 ; $i <= 90 ; ++$i) {
                        $excelValue = $worksheet->getCellByColumnAndRow($i, $row)->getValue();
                        if ($excelValue == '-1') {
                            $error['empty']++;
                            continue;
                        }

                        $eval = new Evaluation();
                        $eval->setPlacement($placement);
                        $eval->setCreatedAt($date);
                        if ($i == 21 or $i == 64) {
                             $eval->setValidated(false);
                        } else {
                            $eval->setValidated(true);
                        }
                        $eval->setModerated(false);

                        if ($i == 15 or $i == 17) {
                            $time = $excelValue . ':' . $worksheet->getCellByColumnAndRow($i+1, $row)->getValue() . ':00';
                            $eval->setEvalCriteria($c[$q[15][$eval_form_id]]);
                            $eval->setValue($time);
                            $this->em->persist($eval);
                        } elseif (isset($q[$i]) and isset($c[$q[$i][$eval_form_id]])) {
                            $eval->setEvalCriteria($c[$q[$i][$eval_form_id]]);

                            if (isset($q[$i][$eval_form_id]['result'])) {
                                $value = $q[$i][$eval_form_id]['result'][$excelValue];
                                $eval->setValue($value);
                            } elseif (isset($q[$i][$eval_form_id]['max'])) {
                                $value = round($excelValue * 100 / $q[$i][$eval_form_id]['max']);
                                $eval->setValue($value);
                            } else {
                                $eval->setValue($excelValue);
                            }
                            $this->em->persist($eval);
                        } else {
                            if (!in_array($i, array(16, 18, 36, 53, 54, 55, 56, 61, 62, 63)) and $i < 65) {
                                $error['form']++;
                                $error['form_row'] .= $row . ': ' . $i . '/' . $eval_form_id . ' - ';
                            }
                        }

                        if (null !== $eval->getValue()) {
                            $valid['eval']++;
                        } elseif (!in_array($i, array(16, 18, 36, 53, 54, 55, 56, 61, 62, 63)) and $i < 65) {
                            $error['empty']++;
                            $error['form_row'] .= $row . ': ' . $i . '/' . $eval_form_id . ' (' . $eval->getEvalCriteria() . ' = ' . $excelValue . ') - ';
                        }
                    }

                }
            }
        }

        $this->get('session')->getFlashBag()->add('error', 'Erreurs (multiples = ' . $error['multiple'] . ', nuls = ' . $error['none'] . ') : ' . $error['row']);
        $this->get('session')->getFlashBag()->add('error', 'Questions non trouvées (' . $error['form'] . ') : ' . $error['form_row']);
        $this->get('session')->getFlashBag()->add('notice', 'Evaluations : ' . $valid['eval'] . ' / ' . $valid['eval'] / ($row - 2 + 1 - $error['none']));
        $this->get('session')->getFlashBag()->add('warning', 'Evaluations : ' . $error['eval'] . ' ; Questions vides : ' . $error['empty']);
        return $this->redirect($this->generateUrl('homepage'));
    }

    /**
     * @Route("/ambu")
     */
    public function importAmbuAction()
    {
        $q = array(
            15 => array(1 => 369, 3 => 316, 4 => 263, 5 => 153, 6 => 209), //Début de stage
            17 => array(1 => 370, 3 => 317, 4 => 264, 5 => 154, 6 => 210), //Fin de stage
            19 => array(1 => 371, 3 => 316, 4 => 265, 5 => 155, 6 => 211), //Demi journées
            20 => array(1 => 372, 3 => 319, 4 => 266, 5 => 156, 6 => 212), //Samedis
            21 => array(1 => 418, 3 => 365, 4 => 312, 5 => 202, 6 => 258), //Commentaire 1
            22 => array(1 => 373, 3 => 320, 4 => 267, 5 => 157, 6 => 213, 'result' => array(0 => 0, 1 => 1, 3 => 2, 5 => 3)), //présentation équipe
            23 => array(1 => 374, 3 => 321, 4 => 268, 5 => 158, 6 => 214, 'result' => array(0 => 0, 1 => 1, 3 => 2, 5 => 3)), //organisation
            24 => array(1 => 375, 3 => 322, 4 => 269, 5 => 159, 6 => 215, 'result' => array(0 => 0, 1 => 1, 3 => 2, 5 => 3)), //objectifs
            25 => array(1 => 376, 3 => 323, 4 => 270, 5 => 160, 6 => 216, 'result' => array(0 => 1, 5 => 0)), //lieu de travail
            26 => array(1 => 378, 3 => 325, 4 => 272, 5 => 162, 6 => 218, 'result' => array(0 => 1, 4 => 0)), //internet
            27 => array(1 => 379, 3 => 326, 4 => 273, 5 => 163, 6 => 219, 'result' => array(0 => 0, 1 => 1, 2 => 2, 3 => 3)), //qualité d es locaux
            28 => array(1 => 380, 3 => 327, 4 => 274, 5 => 164, 6 => 220, 'result' => array(0 => 0, 1 => 1, 2 => 2, 3 => 3)), //ressources docs
            29 => array(1 => 381, 3 => 328, 4 => 275, 5 => 165, 6 => 221, 'result' => array(0 => 0, 5 => 1, 10 => 2)), //prise de responsabilité
            30 => array(1 => 382, 3 => 329, 4 => 276, 5 => 166, 6 => 222, 'result' => array(0 => 0, 5 => 1)), //cours
            31 => array(1 => 383, 3 => 330, 4 => 277, 5 => 167, 6 => 223), //combien
            32 => array(1 => 384, 3 => 331, 4 => 278, 5 => 168, 6 => 224, 'result' => array(0 => 1, 5 => 0)), //staff
            33 => array(1 => 385, 3 => 332, 4 => 279, 5 => 169, 6 => 225, 'result' => array(0 => 0, 3 => 1)), //portfolio
            34 => array(1 => 386, 3 => 333, 4 => 280, 5 => 170, 6 => 226, 'result' => array(0 => 0, 3 => 1, 5 => 2, 8 => 3)), //seniors
            35 => array(1 => 387, 3 => 334, 4 => 281, 5 => 171, 6 => 227, 'max' => 5), //encadrement
            37 => array(1 => 389, 3 => 336, 4 => 283, 5 => 173, 6 => 229, 'result' => array(0 => 2, 3 => 1, 5 => 0)), // pubilcations
            38 => array(1 => 390, 3 => 337, 4 => 284, 5 => 174, 6 => 230, 'result' => array(3 => 0, 4 => 1, 6 => 2)), //thèse
            39 => array(1 => 391, 3 => 338, 4 => 285, 5 => 175, 6 => 231, 'max' => 15), //formateur
            40 => array(1 => 392, 3 => 339, 4 => 286, 5 => 176, 6 => 232, 'result' => array(0 => 1, 15 => 1)), //recommandation
            41 => array(1 => 393, 3 => 340, 4 => 287, 5 => 177, 6 => 233, 'max' => 15), //medecine générale
            42 => array(1 => 394, 3 => 341, 4 => 288, 5 => 178, 6 => 234, 'max' => 10), // comp:décisions
            43 => array(1 => 395, 3 => 342, 4 => 289, 5 => 179, 6 => 235, 'max' => 10), // comp:incertitude
            44 => array(1 => 396, 3 => 343, 4 => 290, 5 => 180, 6 => 236, 'max' => 10), // comp:gestes
            45 => array(1 => 397, 3 => 344, 4 => 291, 5 => 181, 6 => 237, 'max' => 10), // comp:communiquer
            46 => array(1 => 398, 3 => 345, 4 => 292, 5 => 182, 6 => 238, 'max' => 10), // comp:éduquer
            47 => array(1 => 399, 3 => 346, 4 => 293, 5 => 183, 6 => 239, 'max' => 10), // comp:santé pubilque
            48 => array(1 => 400, 3 => 347, 4 => 294, 5 => 184, 6 => 240, 'max' => 10), // comp:équipe
            49 => array(1 => 401, 3 => 348, 4 => 295, 5 => 185, 6 => 241, 'max' => 10), // comp:suivi
            50 => array(1 => 402, 3 => 349, 4 => 296, 5 => 186, 6 => 242, 'max' => 10), // comp:éthique
            51 => array(1 => 403, 3 => 350, 4 => 297, 5 => 187, 6 => 243, 'max' => 10), // comp:gestion
            52 => array(1 => 404, 3 => 351, 4 => 298, 5 => 188, 6 => 244, 'max' => 10), // comp:formation
            56 => array(1 => 405, 3 => 352, 4 => 299, 5 => 189, 6 => 245, 'max' => 16), // charge de travail
            57 => array(1 => 406, 3 => 353, 4 => 300, 5 => 190, 6 => 246, 'result' =>  array(0 => 3, 1 => 3, 2 => 3, 3 => 3, 4 => 3, 5 => 2, 6 => 0, 7 => 2, 8 => 0, 9 => 2, 10 => 1, 11 => 1, 12 => 1, 13 => 1, 14 => 1, 15 => 1, 16 => 1)), // secrétariat
            58 => array(1 => 407, 3 => 354, 4 => 301, 5 => 191, 6 => 247, 'max' => 10), // ambiance:interne
            59 => array(1 => 408, 3 => 355, 4 => 302, 5 => 192, 6 => 248, 'max' => 10), // ambiance:médecins
            60 => array(1 => 409, 3 => 356, 4 => 303, 5 => 193, 6 => 249, 'max' => 10), // ambiance:paramed
            64 => array(1 => 419, 3 => 366, 4 => 313, 5 => 203, 6 => 259), //commentaires libres
//            65 => array(1 => 367, 3 => null, 4 => 261, 5 => 151, 6 => null), //lits
//            66 => array(1 => 368, 3 => null, 4 => 262, 5 => 152, 6 => null), //visites
//            81 => array(1 => null, 3 => null, 4 => 261, 5 => null, 6 => null), //lits péd
//            82 => array(1 => null, 3 => null, 4 => 262, 5 => null, 6 => null), //visites ped
//            83 => array(1 => null, 3 => null, 4 => null, 5 => null, 6 => 204), //fréquentation urg
//            89 => array(1 => null, 3 => null, 4 => null, 5 => null, 6 => 207), //senior présents urg
//            90 => array(1 => null, 3 => null, 4 => null, 5 => null, 6 => 208), //internes nécessaires urg
        );
        $object = $this->get('phpexcel')->createPHPExcelObject(__DIR__.'/../Resources/data/eval_data_1.xls');
        $worksheet = $object->setActiveSheetIndex(0);
        $error['row'] = '';
        $error['multiple'] = 0;
        $error['none'] = 0;
        $error['eval'] = 0;
        $error['empty'] = 0;
        $valid['eval'] = 0;
        $error['form'] = 0;
        $error['form_row'] = '';

        for ($row = 2, $rows = $worksheet->getHighestRow() ; $row <= $rows ; ++$row) {
            if ($placement_id = $worksheet->getCellByColumnAndRow(10, $row)->getValue()) {
                $placement = $this->em->getRepository('GessehCoreBundle:Placement')->find($worksheet->getCellByColumnAndRow(10, $row)->getValue());
                if ($placement) {
                    $c = array();
                    $eval_forms = $this->em->getRepository('GessehEvaluationBundle:EvalForm')->getByPlacement($placement_id);
                    if (count($eval_forms) > 1) {
                        $error['multiple']++;
                        $error['row'] .= $row . ' [';
                        foreach ($eval_forms as $form) {
                            $error['row'] .= $form->getId() . ',';
                            if($form->getId() != 2)
                                $eval_form = $form;
                        }
                        $error['row'] .= '] - ';
                    } elseif (!$eval_forms or $eval_forms[0]->getId() == 2) {
                        $error['none']++;
                        $error['row'] .= $row . ' (' . $placement->getRepartition()->getDepartment()->getName() . ' à ' . $placement->getRepartition()->getDepartment()->getHospital()->getName() . ') - ';
                        continue;
                    } else {
                         $eval_form = $eval_forms[0];
                    }
                    $eval_form_id = $eval_form->getId();
                    foreach($eval_form->getCriterias() as $criteria) {
                         $c[$criteria->getId()] = $criteria;
                    }

                    $excelDate = $worksheet->getCellByColumnAndRow(14, $row)->getValue();
                    $unixDate = ($excelDate - 25569) * 86400;
                    $date = new \DateTime();
                    $date->setTimestamp($unixDate);

                    for ($i = 15 ; $i <= 90 ; ++$i) {
                        $excelValue = $worksheet->getCellByColumnAndRow($i, $row)->getValue();
                        if ($excelValue == '-1') {
                            $error['empty']++;
                            continue;
                        }

                        $eval = new Evaluation();
                        $eval->setPlacement($placement);
                        $eval->setCreatedAt($date);
                        if ($i == 21 or $i == 64) {
                             $eval->setValidated(false);
                        } else {
                            $eval->setValidated(true);
                        }
                        $eval->setModerated(false);

                        if ($i == 15 or $i == 17) {
                            $time = $excelValue . ':' . $worksheet->getCellByColumnAndRow($i+1, $row)->getValue() . ':00';
                            $eval->setEvalCriteria($c[$q[15][$eval_form_id]]);
                            $eval->setValue($time);
                            $this->em->persist($eval);
                        } elseif (isset($q[$i]) and isset($c[$q[$i][$eval_form_id]])) {
                            $eval->setEvalCriteria($c[$q[$i][$eval_form_id]]);

                            if (isset($q[$i][$eval_form_id]['result'])) {
                                $value = $q[$i][$eval_form_id]['result'][$excelValue];
                                $eval->setValue($value);
                            } elseif (isset($q[$i][$eval_form_id]['max'])) {
                                $value = round($excelValue * 100 / $q[$i][$eval_form_id]['max']);
                                $eval->setValue($value);
                            } else {
                                $eval->setValue($excelValue);
                            }
                            $this->em->persist($eval);
                        } else {
                            if (!in_array($i, array(16, 18, 36, 53, 54, 55, 56, 61, 62, 63)) and $i < 65) {
                                $error['form']++;
                                $error['form_row'] .= $row . ': ' . $i . '/' . $eval_form_id . ' - ';
                            }
                        }

                        if (null !== $eval->getValue()) {
                            $valid['eval']++;
                        } elseif (!in_array($i, array(16, 18, 36, 53, 54, 55, 56, 61, 62, 63)) and $i < 65) {
                            $error['empty']++;
                            $error['form_row'] .= $row . ': ' . $i . '/' . $eval_form_id . ' (' . $eval->getEvalCriteria() . ' = ' . $excelValue . ') - ';
                        }
                    }

                }
            }
        }

        $this->get('session')->getFlashBag()->add('error', 'Erreurs (multiples = ' . $error['multiple'] . ', nuls = ' . $error['none'] . ') : ' . $error['row']);
        $this->get('session')->getFlashBag()->add('error', 'Questions non trouvées (' . $error['form'] . ') : ' . $error['form_row']);
        $this->get('session')->getFlashBag()->add('notice', 'Evaluations : ' . $valid['eval'] . ' / ' . $valid['eval'] / ($row - 2 + 1 - $error['none']));
        $this->get('session')->getFlashBag()->add('warning', 'Evaluations : ' . $error['eval'] . ' ; Questions vides : ' . $error['empty']);
        return $this->redirect($this->generateUrl('homepage'));
    }
}
