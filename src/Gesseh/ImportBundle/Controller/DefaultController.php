<?php

namespace Gesseh\ImportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Finder\Finder;
use Gesseh\UserBundle\Entity\Student;
use Gesseh\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * Import controller
 *
 * @Route("/import")
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
     * @Route("/prepare/first")
     */
    public function prepareStudentAndFieldsetAction()
    {
        $object = $this->get('phpexcel')->createPHPExcelObject(__DIR__.'/../Resources/data/eval_data.xlsx');
        $worksheet = $object->setActiveSheetIndex();
        $error['row'] = 0;
        $rows = $worksheet->getHighestRow();
        $row = 2;

        for ($worksheet->getCellByColumnAndRow(0, $row)->getValue() ; $row <= $rows ; ++$row) {
            $lastname = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
            $secondname = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
            $firstname = $worksheet->getCellByColumnAndRow(4, $row)->getValue();

            $student = $this->em->getRepository('GessehUserBundle:Student')->findOneBy(array('surname' => $lastname, 'name' => $firstname));

            if ($student)
                $worksheet->getCellByColumnAndRow(0, $row)->setValue($student->getId());
            else
                $worksheet->getCellByColumnAndRow(0, $row)->setValue();

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
     * @Route("/prepare/placement")
     */
    public function preparePlacementAction()
    {
        $object = $this->get('phpexcel')->createPHPExcelObject(__DIR__.'/../Resources/data/eval_data.xls');
        $worksheet = $object->setActiveSheetIndex();
        $error['row'] = 0;
        $rows = $worksheet->getHighestRow();
        $row = 2;

        for ($worksheet->getCellByColumnAndRow(0, $row)->getValue() ; $row <= $rows ; ++$row) {
            $student_id = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
            $department_id = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
            $placements = $this->em->getRepository('GessehCoreBundle:Placement')->getByStudentAndDepartment($student_id, $department_id);

            if ($placements) {
                if (count($placements) > 1) {
                     $worksheet->getCellByColumnAndRow(10, $row)->setValue('__multi__');
                } else {
                     $worksheet->getCellByColumnAndRow(10, $row)->setValue($placements[0]->getId());
                }
            } else {
                 $worksheet->getCellByColumnAndRow(10, $row)->setValue();
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
}
