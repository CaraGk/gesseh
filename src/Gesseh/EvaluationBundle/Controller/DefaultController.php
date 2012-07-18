<?php

namespace Gesseh\EvaluationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Gesseh\CoreBundle\Entity\Department;
use Gesseh\EvaluationBundle\Entity\Evaluation;
use Gesseh\EvaluationBundle\Entity\EvalSector;

class DefaultController extends Controller
{
    /**
     * @Route("/u/e/{id}/show", name="GEval_DShow", requirements={"id" = "\d+"})
     * @Template()
     */
    public function showAction($id)
    {
      $em = $this->getDoctrine()->getEntityManager();
      $department = $em->getRepository('GessehCoreBundle:Department')->find($id);

      if (!$department)
        throw $this->createNotFoundException('Unable to find department entity.');

      $eval_text = $em->getRepository('GessehEvaluationBundle:Evaluation')->getTextByDepartment($id);
      $eval_num = $em->getRepository('GessehEvaluationBundle:Evaluation')->getNumByDepartment($id);
      $eval_form = $em->getRepository('GessehEvaluationBundle:EvalSector')->getEvalFormBySector($department->getSector()->getId());

      return array(
        'eval_text'  => $eval_text,
        'eval_num'   => $eval_num,
        'department' => $department,
        'eval_form'  => $eval_form,
      );
    }

    /**
     * @Route("/u/e/{id}/eval", name="GEval_DEval", requirements={"id" = "\d+"})
     * @Template()
     */
    public function evaluateAction($id)
    {
      $em = $this->getDoctrine()->getEntityManager();
      $department = $em->getRepository('GessehCoreBundle:Department')->find($id);
      $eval_form = $em->getRepository('GessehEvaluationBundle:EvalSector')->getEvalFormBySector($department->getSector()->getId());
    }
}
