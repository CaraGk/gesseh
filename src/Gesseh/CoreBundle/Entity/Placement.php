<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2013-2016 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Gesseh\CoreBundle\Entity\Placement
 *
 * @ORM\Table(name="placement")
 * @ORM\Entity(repositoryClass="Gesseh\CoreBundle\Entity\PlacementRepository")
 */
class Placement
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Gesseh\UserBundle\Entity\Student")
     * @ORM\JoinColumn(name="student_id", referencedColumnName="id")
     * @Assert\NotBlank()
     * @Assert\Type(type="Gesseh\UserBundle\Entity\Student")
     */
    private $student;

    /**
     * @ORM\ManyToOne(targetEntity="Repartition", inversedBy="placements", cascade={"persist"})
     * @ORM\JoinColumn(name="repartition_id", referencedColumnName="id")
     * @Assert\NotBlank()
     * @Assert\Type(type="Gesseh\CoreBundle\Entity\Repartition")
     */
    private $repartition;

    public function __toString()
    {
      return $this->repartition->getDepartment()->getName() . " à " . $this->repartition->getDepartment()->getHospital()->getName();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set student
     *
     * @param Gesseh\UserBundle\Entity\Student $student
     */
    public function setStudent(\Gesseh\UserBundle\Entity\Student $student)
    {
        $this->student = $student;
    }

    /**
     * Get student
     *
     * @return Gesseh\UserBundle\Entity\Student
     */
    public function getStudent()
    {
        return $this->student;
    }

    /**
    /**
     * Set repartition
     *
     * @param Gesseh\CoreBundle\Entity\Repartition $repartition
     */
    public function setRepartition(\Gesseh\CoreBundle\Entity\Repartition $repartition)
    {
        $this->repartition = $repartition;
    }

    /**
     * Get repartition
     *
     * @return Gesseh\CoreBundle\Entity\Repartition
     */
    public function getRepartition()
    {
        return $this->repartition;
    }
}
