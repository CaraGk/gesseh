<?php

namespace Gesseh\MigrateOldDbBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Gesseh\UserBundle\Entity\Student;
use Gesseh\UserBundle\Entity\User;
use Gesseh\UserBundle\Entity\Grade;
use Gesseh\CoreBundle\Entity\Department;
use Gesseh\CoreBundle\Entity\Hospital;
use Gesseh\CoreBundle\Entity\Period;
use Gesseh\CoreBundle\Entity\Placement;
use Gesseh\CoreBundle\Entity\Sector;
use Gesseh\SimulationBundle\Entity\SimulPeriod;
use Gesseh\SimulationBundle\Entity\Wish;
use Gesseh\SimulationBundle\Entity\Simulation;
use Gesseh\EvaluationBundle\Entity\EvalCriteria;
use Gesseh\EvaluationBundle\Entity\EvalForm;
use Gesseh\EvaluationBundle\Entity\Evaluation;

/**
 * @Route("/admin/migrate")
 */

class DefaultController extends Controller
{
    /**
     * @Route("/", name="GMODB_Index")
     * @Template()
     */
    public function indexAction()
    {
      $em = $this->getDoctrine()->getEntityManager();
      $etudiants = $em->getRepository('GessehMigrateOldDbBundle:GessehEtudiant')->findAll();
      $promos = $em->getRepository('GessehMigrateOldDbBundle:GessehPromo')->findAll();
      $hopitals = $em->getRepository('GessehMigrateOldDbBundle:GessehHopital')->findAll();
      $choixs = $em->getRepository('GessehMigrateOldDbBundle:GessehChoix')->findAll();
      $criteres = $em->getRepository('GessehMigrateOldDbBundle:GessehCritere')->findAll();
      $evals = $em->getRepository('GessehMigrateOldDbBundle:GessehEval')->findAll();
      $filieres = $em->getRepository('GessehMigrateOldDbBundle:GessehFiliere')->findAll();
      $formevals = $em->getRepository('GessehMigrateOldDbBundle:GessehFormEval')->findAll();
      $periodes = $em->getRepository('GessehMigrateOldDbBundle:GessehPeriode')->findAll();
      $simulations = $em->getRepository('GessehMigrateOldDbBundle:GessehSimulation')->findAll();
      $stages = $em->getRepository('GessehMigrateOldDbBundle:GessehStage')->findAll();
      $terrains = $em->getRepository('GessehMigrateOldDbBundle:GessehTerrain')->findAll();

      $count['grade'] = $count['student'] = $count['sector'] = $count['hospital'] = $count['department'] = $count['period'] = $count['simulperiod'] = $count['placement'] = $count['wish'] = $count['simulation'] = $count['evalform'] = $count['evalcriteria'] = $count['evaluation'] = 0;

      foreach ($promos as $promo) {
        $gr_name = 'grade_' . $promo->getId();
        $$gr_name = new Grade();
        $$gr_name->setName($promo->getTitre());
        $$gr_name->setRank($promo->getOrdre());
        $$gr_name->setIsActive($promo->getActive());

        $em->persist($$gr_name);
        $count['grade']++;
      }

      foreach ($etudiants as $etudiant) {
        $et_name = 'student_' . $etudiant->getId();

        if (null == $etudiant->getUtilisateur()->getLastName())
          continue;

        $$et_name = new Student();
        $$et_name->setSurname($etudiant->getUtilisateur()->getLastName());
        $$et_name->setName($etudiant->getUtilisateur()->getFirstName());
        $$et_name->setPhone($etudiant->getTel());

        $us_name = 'user_' . $etudiant->getUtilisateur()->getId();
        $$us_name = new User();
        $$us_name->setPlainPassword($this->generatePwd(8));
        $$us_name->setConfirmationToken(null);
        $$us_name->setEnabled(true);
        $$us_name->addRole('ROLE_STUDENT');
        $$us_name->setEmail($etudiant->getUtilisateur()->getEmailAddress());
        $$us_name->setUsername($etudiant->getUtilisateur()->getEmailAddress());
        $em->persist($$us_name);
        $$et_name->setUser($$us_name);

        $gr_name = 'grade_' . $etudiant->getPromoId()->getId();
        $$et_name->setGrade($$gr_name);

        $$et_name->setRanking($etudiant->getClassement());
        $$et_name->setGraduate($etudiant->getAnneePromo());
        $$et_name->setAnonymous($etudiant->getAnonyme());

        $em->persist($$et_name);
        $count['student']++;
      }

      foreach ($filieres as $filiere) {
        $fi_name = 'sector_' . $filiere->getId();
        $$fi_name = new Sector();
        $$fi_name->setName($filiere->getTitre());

        $em->persist($$fi_name);
        $count['sector']++;
      }

      foreach ($hopitals as $hopital) {
        $ho_name = 'hospital_' . $hopital->getId();
        $$ho_name = new Hospital();
        $$ho_name->setName($hopital->getTitre());
        $$ho_name->setAddress($hopital->getAdresse());
        $$ho_name->setWeb($hopital->getWeb());
        $$ho_name->setPhone($hopital->getTelephone());
        $$ho_name->setDescription($hopital->getPage());

        $em->persist($$ho_name);
        $count['hospital']++;
      }

      foreach ($terrains as $terrain) {
        $te_name = 'department_' . $terrain->getId();
        $$te_name = new Department();
        $$te_name->setName($terrain->getTitre());
        $$te_name->setHead($terrain->getPatron());
        $$te_name->setDescription($terrain->getPage());

        $ho_name = 'hospital_' . $terrain->getHopitalId()->getId();
        $$te_name->setHospital($$ho_name);

        $fi_name = 'sector_' . $terrain->getFiliere()->getId();
        $$te_name->setSector($$fi_name);

        $$te_name->setNumber($terrain->getTotal());

        $em->persist($$te_name);
        $count['department']++;
      }

      foreach ($periodes as $periode) {
        $pe_name = 'period_' . $periode->getId();
        $$pe_name = new Period();
        $$pe_name->setBegin($periode->getDebut());
        $$pe_name->setEnd($periode->getFin());

        if ($periode->getDebutSimul() != null) {
          $sp_name = 'simulperiod_' . $periode->getId();
          $$sp_name = new SimulPeriod();
          $$sp_name->setBegin($periode->getDebutSimul());
          $$sp_name->setEnd($periode->getFinSimul());
          $$sp_name->setPeriod($$pe_name);

          $em->persist($$sp_name);
          $count['simulperiod']++;
        }

        $em->persist($$pe_name);
        $count['period']++;
      }

      foreach ($stages as $stage) {
        $st_name = 'placement_' . $stage->getId();
        $$st_name = new Placement();

        $pe_name = 'period_' . $stage->getPeriodeId()->getId();
        $$st_name->setPeriod($$pe_name);

        $et_name = 'student_' . $stage->getEtudiantId()->getId();
        $$st_name->setStudent($$et_name);

        $te_name = 'department_' . $stage->getTerrainId()->getId();
        $$st_name->setDepartment($$te_name);

        $em->persist($$st_name);
        $count['placement']++;
      }

      foreach ($simulations as $simulation) {
        $si_name = 'simulation_' . $simulation->getEtudiant()->getId();
        $$si_name = new Simulation();
        $$si_name->setId($simulation->getId());

        $et_name = 'student_' . $simulation->getEtudiant()->getId();
        $$si_name->setStudent($$et_name);

        if (null !== $simulation->getPoste()) {
          $te_name = 'department_' . $simulation->getPoste()->getId();
          $$si_name->setDepartment($$te_name);
        }

        $$si_name->setExtra($simulation->getReste());

        if ($simulation->getAbsent() == true)
          $$si_name->setActive(false);
        else
          $$si_name->setActive(true);

        $em->persist($$si_name);
        $count['simulation']++;
      }

      foreach ($choixs as $choix) {
        $ch_name = 'wish_' . $choix->getId();
        $$ch_name = new Wish();

        $te_name = 'department_' . $choix->getPoste()->getId();
        $$ch_name->setDepartment($$te_name);

        $$ch_name->setRank($choix->getOrdre());

        $si_name = 'simulation_' . $choix->getEtudiant()->getId();
        $$ch_name->setSimstudent($$si_name);

        $em->persist($$ch_name);
        $count['wish']++;
      }

      foreach ($formevals as $formeval) {
        $fe_name = 'evalform_' . $formeval->getId();
        $$fe_name = new EvalForm();
        $$fe_name->setName($formeval->getTitre());

        $em->persist($$fe_name);
        $count['evalform']++;
      }

      foreach ($criteres as $critere) {
        $cr_name = 'evalcriteria_' . $critere->getId();
        $$cr_name = new EvalCriteria();
        $$cr_name->setName($critere->getTitre());

        if ($critere->getType() == 'radio')
          $$cr_name->setType(1);
        elseif ($critere->getType() == 'text')
          $$cr_name->setType(2);

        if (null != $critere->getRatio()) {
          $string = $critere->getRatio() . '|' . str_pad(null, $critere->getRatio() - 1, ',');
          $$cr_name->setMore($string);
        }

        $$cr_name->setRank($critere->getOrdre());

        $fe_name = 'evalform_' . $critere->getForm()->getId();
        $$cr_name->setEvalForm($$fe_name);

        $em->persist($$cr_name);
        $count['evalcriteria']++;
      }

      foreach ($evals as $eval) {
        $ev_name = 'evaluation_' . $eval->getId();
        $$ev_name = new Evaluation();

        $st_name = 'placement_' . $eval->getStageId()->getId();
        $$ev_name->setPlacement($$st_name);

        $cr_name = 'evalcriteria_' . $eval->getCritereId()->getId();
        $$ev_name->setEvalCriteria($$cr_name);

        $$ev_name->setValue($eval->getValeur());
        $$ev_name->setCreatedAt($eval->getCreatedAt());

        $em->persist($$ev_name);
        $count['evaluation']++;
      }

      $em->flush();

      return array(
        'count' => $count
      );
    }

  private function generatePwd($length)
  {
    $characters = array ('a','z','e','r','t','y','u','p','q','s','d','f','g','h','j','k','m','w','x','c','v','b','n','2','3','4','5','6','7','8','9','A','Z','E','R','T','Y','U','P','S','D','F','G','H','J','K','L','M','W','X','C','V','B','N');
    $password = '';

    for ($i = 0 ; $i < $length ; $i++) {
      $rand = array_rand($characters);
      $password .= $characters[$rand];
    }

    return $password;
  }
}
