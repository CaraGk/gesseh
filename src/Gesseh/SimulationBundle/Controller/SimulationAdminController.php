<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2013-2016 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\SimulationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Gesseh\SimulationBundle\Entity\Wish;
use Gesseh\SimulationBundle\Form\WishType;
use Gesseh\SimulationBundle\Form\WishHandler;
use Gesseh\SimulationBundle\Entity\Simulation;
use Gesseh\CoreBundle\Entity\Placement;
use Gesseh\SimulationBundle\Entity\SimulPeriod;
use Gesseh\SimulationBundle\Form\SimulPeriodType;
use Gesseh\SimulationBundle\Form\SimulPeriodHandler;
use Gesseh\SimulationBundle\Entity\SectorRule;
use Gesseh\SimulationBundle\Form\SectorRuleType;
use Gesseh\SimulationBundle\Form\SectorRuleHandler;
use Gesseh\SimulationBundle\Form\SimulationType;
use Symfony\Component\Validator\Constraints\File;

/**
 * Simulation admin controller
 *
 * @Route("/admin/simulation")
 */
class SimulationAdminController extends Controller
{
    /**
     * @Route("/", name="GSimul_SAList")
     * @Template()
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $simulations_query = $em->getRepository('GessehSimulationBundle:Simulation')->getAll();
        $simul_missing = $em->getRepository('GessehSimulationBundle:Simulation')->countMissing();
        $simul_total = $em->getRepository('GessehSimulationBundle:Simulation')->countTotal();

        return array(
            'simulations'   => $simulations_query->getResult(),
            'simul_missing' => $simul_missing,
            'simul_total'   => $simul_total,
        );
    }

    /**
     * Set Simstudent's rank up
     *
     * @Route("/{id}/up", name="GSimul_SAUp", requirements={"id" = "\d+"})
     */
    public function setRankUpAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $simulation = $em->getRepository('GessehSimulationBundle:Simulation')->find($id);
        $rank = $simulation->getRank();

        if ($rank > 1) {
            $simulation_before = $em->getRepository('GessehSimulationBundle:Simulation')->findOneByRank($rank - 1);

            $simulation_before->setRank($rank);
            $simulation->setRank($rank - 1);

            $em->persist($simulation);
            $em->persist($simulation_before);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice', 'Étudiant ' . $simulation->getStudent() . ' déplacé au rang ' . $simulation->getRank() . '.');
            $this->get('session')->getFlashBag()->add('notice', 'Étudiant ' . $simulation_before->getStudent() . ' déplacé au rang ' . $simulation_before->getRank() . '.');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Étudiant ' . $simulation->getStudent() . ' est déjà le premier de la liste !');
        }

        return $this->redirect($this->generateUrl('GSimul_SAList'));
    }

    /**
     * Set Simstudent's rank down
     *
     * @Route("/{id}/down", name="GSimul_SADown", requirements={"id" = "\d+"})
     */
    public function setRankDownAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $simulation = $em->getRepository('GessehSimulationBundle:Simulation')->find($id);
        $rank = $simulation->getRank();
        $simulation_total = $em->getRepository('GessehSimulationBundle:Simulation')->countTotal();

        if ($rank < $simulation_total) {
            $simulation_after = $em->getRepository('GessehSimulationBundle:Simulation')->findOneByRank($rank + 1);

            $simulation_after->setRank($rank);
            $simulation->setRank($rank + 1);

            $em->persist($simulation);
            $em->persist($simulation_after);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice', 'Étudiant ' . $simulation->getStudent() . ' déplacé au rang ' . $simulation->getRank() . '.');
            $this->get('session')->getFlashBag()->add('notice', 'Étudiant ' . $simulation_after->getStudent() . ' déplacé au rang ' . $simulation_after->getRank() . '.');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Étudiant ' . $simulation->getStudent() . ' est déjà le dernier de la liste !');
        }

        return $this->redirect($this->generateUrl('GSimul_SAList'));
    }

    /**
     * @Route("/live/repartition", name="GSimul_SALiveRepart")
     * @Template()
     */
    public function liveRepartAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $simulations_query = $em->getRepository('GessehSimulationBundle:Simulation')->getAll();
        $simul_missing = $em->getRepository('GessehSimulationBundle:Simulation')->countMissing();
        $simul_total = $em->getRepository('GessehSimulationBundle:Simulation')->countTotal();
        $simulations = $paginator->paginate( $simulations_query, $this->get('request')->query->get('page', 1), 20);

        return array(
            'simulations'   => $simulations,
            'simul_missing' => $simul_missing,
            'simul_total'   => $simul_total,
        );
    }

    /**
     * @Route("/live/simulation", name="GSimul_SALiveSimul")
     */
    public function liveSimulAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        if ($request->isXmlHttpRequest()) {
            $response = null;
            $id = $request->get('id');
            $simulation = $em->getRepository('GessehSimulationBundle:Simulation')->find($id);
            if (!$simulation) {
                $response = new JsonResponse(array('message' => 'Error: Unknown entity.'), 412);
            }
            $form = $this->createForm(new SimulationType(), $simulation);

            if ($request->isMethod('POST')) {
                $form->bind($request);

                if ($form->isValid()) {
                    $simul = $form->getData();
                    $simul->setIsValidated(true);
                    $em->persist($simul);
                    $em->flush();

                    $response = new JsonResponse(array(
                        'message'=> 'Success !',
                        'entity' => array(
                            'department'  => $simulation->getDepartment()->getHospital()->getName() . ' : ' . $simulation->getDepartment()->getName(),
                            'isExcess'    => $simulation->isExcess(),
                            'isValidated' => $simulation->isValidated(),
                            'isActive'    => $simulation->getActive(),
                        ),

                    ), 200);
                } else {
                    $response = new JsonResponse(array(
                        'message'    => 'Errors in the form !',
                    ), 412);
                }
            }

            if (!$response) {
                $response = new JsonResponse(array(
                    'message' => 'Use the form !',
                    'form'    => $this->renderView('GessehSimulationBundle:SimulationAdmin:form.html.twig', array(
                        'entity' => $simulation,
                        'form'   => $form->createView(),
                    ))), 200)
                ;
            }
        }
        return $response;
    }

    /**
     * Affiche la liste des poste restants lors de la répartition
     *
     * @Route("/live/left", name="GSimul_SALiveLeft")
     * @Template()
     */
    public function liveLeftAction(Request $request)
    {
      $em = $this->getDoctrine()->getManager();

      $last_period = $em->getRepository('GessehCoreBundle:Period')->getLast();
      $sector = $em->getRepository('GessehCoreBundle:Sector')->getNext($request->get('sector', 0));
      if (!$sector)
          $sector = $em->getRepository('GessehCoreBundle:Sector')->getNext();
      $left = array();

      $sectors = $em->getRepository('GessehCoreBundle:Sector')->findAll();
      $repartitions = $em->getRepository('GessehCoreBundle:Repartition')->getAvailableForSector($last_period, $sector->getId());
      $sims = $em->getRepository('GessehSimulationBundle:Simulation')->getDepartmentLeftForSector($sector->getId(), $last_period);

      foreach($sims as $sim) {
        $extra = $sim->getExtra();
        foreach($sim->getDepartment()->getRepartitions() as $repartition) {
          if($cluster_name = $repartition->getCluster()) {
            foreach($em->getRepository('GessehCoreBundle:Repartition')->getByPeriodAndCluster($last_period, $cluster_name) as $other_repartition) {
              $left[$other_repartition->getDepartment()->getId()] = $extra;
            }
          }
        }
        $left[$repartition->getDepartment()->getId()] = $extra;
      }

      return array(
        'repartitions' => $repartitions,
        'left'         => $left,
        'cur_sector'   => $sector,
        'sectors'      => $sectors,
      );
    }


    /**
     * @Route("/period/simul/{id}", name="GSimul_SAPeriod", requirements={"id" = "\d+"})
     * @Template()
     */
    public function periodAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $period = $em->getRepository('GessehCoreBundle:Period')->find($id);
        $simul_period = $em->getRepository('GessehSimulationBundle:SimulPeriod')->findOneByPeriod($id);

        if (!$simul_period) {
            $simul_period = new SimulPeriod();
            $simul_period->setPeriod($period);
        }

        $form = $this->createForm(new SimulPeriodType(), $simul_period);
        $form_handler = new SimulPeriodHandler($form, $this->get('request'), $em, $period);

        if ($form_handler->process()) {
            $this->get('session')->getFlashBag()->add('notice', 'Session de simulations du "' . $simul_period->getBegin()->format('d-m-Y') . '" au "' . $simul_period->getEnd()->format('d-m-Y') . '" modifiée.');
            return $this->redirect($this->generateUrl('GCore_PAPeriodIndex'));
        }

        return array(
            'period'      => $period,
            'period_form' => $form->createView(),
            'simul_period'=> $simul_period,
        );
    }

    /**
     * @Route("/period/simul/{id}/delete", name="GSimul_SAPeriodDelete", requirements={"id" = "\d+"})
     */
    public function deletePeriodAction($id)
    {
      $em = $this->getDoctrine()->getManager();
      $simul_period = $em->getRepository('GessehSimulationBundle:SimulPeriod')->find($id);

      if (!$simul_period)
        throw $this->createNotFoundException('Unable to find simul_period entity.');

      $em->remove($simul_period);
      $em->flush();

      $this->get('session')->getFlashBag()->add('notice', 'Session de simulations du "' . $simul_period->getBegin()->format('d-m-Y') . '" au "' . $simul_period->getEnd()->format('d-m-Y') . '" supprimée.');

      return $this->redirect($this->generateUrl('GCore_PAPeriodIndex'));
    }

    /**
     * @Route("/define", name="GSimul_SADefine")
     */
    public function defineAction()
    {
      $em = $this->getDoctrine()->getManager();
      $students = $em->getRepository('GessehUserBundle:Student')->getRankingOrder();
      $count = $em->getRepository('GessehSimulationBundle:Simulation')->setSimulationTable($students, $em);

      if ($count) {
        $this->get('session')->getFlashBag()->add('notice', $count . ' étudiants enregistrés dans la table de simulation.');
      } else {
        $this->get('session')->getFlashBag()->add('error', 'Attention : Aucun étudiant enregistré dans la table de simulation.');
      }

      return $this->redirect($this->generateUrl('GSimul_SAList'));
    }

    /**
     * @Route("/define/import", name="GSimul_SAImport")
     * @Template("GessehUserBundle:StudentAdmin:import.html.twig")
     */
    public function importAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $error = 0;
        $form = $this->createFormBuilder()
            ->add('file', 'file', array(
                'label'    => 'Fichier',
                'required' => true,
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

                if ($student_rank = $em->getRepository('GessehSimulationBundle:Simulation')->getLast())
                    $count = (int) $student_rank->getRank() + 1;
                else
                    $count = 2;
                $student_count = 0;
                $error = 0;

                while ($rank = $objPHPExcel->getCellByColumnAndRow(0, $count)) {
                    $name['last'] = strtolower($objPHPExcel->getCellByColumnAndRow(1, $count));
                    $name['alt'] = strtolower($objPHPExcel->getCellByColumnAndRow(2, $count));
                    $name['first'] = strtolower($objPHPExcel->getCellByColumnAndRow(3, $count));

                    if ($students = $em->getRepository('GessehUserBundle:Student')->searchExact($name))
                    {
                        if (count($students) < 2) {
                            $simulation = new Simulation();
                            $simulation->setId($rank);
                            $simulation->setStudent($students[0]);
                            $simulation->setRank($rank);
                            $simulation->setActive(true);
                            $em->persist($simulation);
                            $student_count++;
                        } else {
                            $error++;
                        }
                    } else {
                        $error++;
                    }
                    if (in_array($count, array(200, 400, 600, 800))) {
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('notice', $student_count . ' étudiants enregistrés dans la table de simulation.');
                        $this->get('session')->getFlashBag()->add('error', $error . ' étudiants ont posé problème.');
                        $this->redirect('GSimul_SAImport');
                    }
                    $count++;
                }

                $this->get('session')->getFlashBag()->add('notice', $student_count . ' étudiants enregistrés dans la table de simulation.');
                $this->get('session')->getFlashBag()->add('error', $error . ' étudiants ont posé problème.');
                $this->redirect('GSimul_SAList');
            }
        }

        return array(
            'form'  => $form->createView(),
            'error' => $error,
        );
    }

    /**
     * @Route("/purge", name="GSimul_SAPurge")
     */
    public function purgeAction()
    {
      $em = $this->getDoctrine()->getManager();
      $sims = $em->getRepository('GessehSimulationBundle:Simulation')->findAll();

      foreach ($sims as $sim) {
        $em->remove($sim);
      }

      $em->flush();

      $this->get('session')->getFlashBag()->add('notice', "Les données de la simulation ont été supprimées.");

      return $this->redirect($this->generateUrl('GSimul_SAList'));
    }

    /**
     * @Route("/save", name="GSimul_SASave")
     */
    public function saveAction()
    {
        $em = $this->getDoctrine()->getManager();

        if ($em->getRepository('GessehSimulationBundle:SimulPeriod')->isSimulationActive()) {
            $this->get('session')->getFlashBag()->add('error', 'La simulation est toujours active ! Vous ne pourrez la valider qu\'une fois qu\'elle sera inactive. Aucune donnée n\'a été copiée.');

            return $this->redirect($this->generateUrl('GSimul_SAList'));
        }

        $sims = $em->getRepository('GessehSimulationBundle:Simulation')->getAllValid();
        $simulPeriod = $em->getRepository('GessehSimulationBundle:SimulPeriod')->getLastActive();
        if (!$simulPeriod) {
            $this->get('session')->getFlashBag()->add('error', 'Il n\'y a aucune simulation antérieure retrouvée.');

            return $this->redirect($this->generateUrl('GSimul_SAList'));
        } else {
            $period = $simulPeriod->getPeriod();
        }

        $error = array('total' => 0, 'details' => '');
        foreach ($sims as $sim) {
            if ($current_repartition = $sim->getDepartment()->findRepartition($period)) {
                if($cluster_name = $current_repartition->getCluster()) {
                    $other_repartitions = $em->getRepository('GessehCoreBundle:Repartition')->getByPeriodAndCluster($period->getId(), $cluster_name);

                    foreach ($other_repartitions as $repartition) {
                        $placement = new Placement();
                        $placement->setStudent($sim->getStudent());
                        $placement->setRepartition($repartition);
                        $em->persist($placement);
                    }
                }
                $placement = new Placement();
                $placement->setStudent($sim->getStudent());
                $placement->setRepartition($current_repartition);
                $em->persist($placement);
            } else {
                $error['total']++;
                $error['details'] .= ' [' . $sim->getStudent() . '|' . $sim->getDepartment() . ':' . $sim->getExtra() . '] ';
            }
        }

        $em->flush();

        $this->get('session')->getFlashBag()->add('notice', 'Les données de la simulation ont été copiées dans les stages.');
        if ($error['total'])
            $this->get('session')->getFlashBag()->add('warning', 'Il y a eu ' . $error['total'] . ' erreurs d\'enregistrement.' . $error['details']);

        return $this->redirect($this->generateUrl('GSimul_SAPurge'));
    }

    /**
     * Affiche un tableau de SectorRule
     *
     * @Route("/s/", name="GSimul_SARule")
     * @Template()
     */
    public function ruleAction()
    {
      $em = $this->getDoctrine()->getManager();
      $rules = $em->getRepository('GessehSimulationBundle:SectorRule')->getAll();

      return array(
        'rules'     => $rules,
        'rule_form' => null,
      );
    }

    /**
     * Affiche un formulaire d'ajout de SectorRule
     *
     * @Route("/s/new", name="GSimul_SANewRule")
     * @Template("GessehSimulationBundle:SimulationAdmin:rule.html.twig")
     */
    public function newRuleAction()
    {
      $em = $this->getDoctrine()->getManager();
      $rules = $em->getRepository('GessehSimulationBundle:SectorRule')->getAll();

      $sector_rule = new SectorRule();
      $form = $this->createForm(new SectorRuleType(), $sector_rule);
      $form_handler = new SectorRuleHandler($form, $this->get('request'), $em);

      if ($form_handler->process()) {
        $this->get('session')->getFlashBag()->add('notice', 'Relation entre "' . $sector_rule->getSector()->getName() . '" et "' . $sector_rule->getGrade()->getName() . '" ajoutée.');

        return $this->redirect($this->generateUrl('GSimul_SARule'));
      }

      return array(
        'rules'     => $rules,
        'rule_form' => $form->createView(),
      );
    }

    /**
     * Supprime un SectorRule
     *
     * @Route("/s/{id}/d", name="GSimul_SADeleteRule", requirements={"id" = "\d+"})
     */
    public function deleteRuleAction($id)
    {
      $em = $this->getDoctrine()->getManager();
      $rule = $em->getRepository('GessehSimulationBundle:SectorRule')->find($id);

      if (!$rule)
        throw $this->createNotFoundException('Unable to find sector_rule entity.');

      $em->remove($rule);
      $em->flush();

      $this->get('session')->getFlashBag()->add('notice', 'Règle de simulation pour "' . $rule . '" supprimée.');

      return $this->redirect($this->generateUrl('GSimul_SARule'));
    }
}
