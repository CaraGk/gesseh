<?php

namespace Gesseh\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class StudentController extends Controller
{
    /**
     * @Route("/u")
     * @Template()
     */
    public function indexAction($name)
    {
        return array('name' => $name);
    }
}
