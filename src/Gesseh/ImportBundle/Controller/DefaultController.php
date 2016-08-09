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
     * @Route("/prepare")
     */
    public function prepareAction()
    {
        $object = $this->get('phpexcel')->createPHPExcelObject(__DIR__.'/../Resources/data/eval_data.xlsx');
        $worksheet = $object->setActiveSheetIndex();
        $error['row'] = 0;
        $rows = $worksheet->getHighestRow();
        $row = 2;

        for ($worksheet->getCellByColumnAndRow(0, $row)->getValue() ; $row <= $rows ; ++$row) {
            $lastname = strtolower($worksheet->getCellByColumnAndRow(2, $row)->getValue());
            $secondname = strtolower($worksheet->getCellByColumnAndRow(3, $row)->getValue());
            $firstname = strtolower($worksheet->getCellByColumnAndRow(4, $row)->getValue());

            $student = $this->em->getRepository('GessehUserBundle:Student')->findOneBy(array('surname' => $lastname, 'name' => $firstname));

            if ($student)
                $worksheet->getCellByColumnAndRow(0, $row)->setValue($student->getId());
            else
                $worksheet->getCellByColumnAndRow(0, $row)->setValue();

            $hospital_title = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
            $department_title = $worksheet->getCellByColumnAndRow(9, $row)->getValue();

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
}
