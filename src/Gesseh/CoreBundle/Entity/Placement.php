<?php

namespace Gesseh\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Gesseh\CoreBundle\Entity\Placement
 *
 * @ORM\Table(name="placement")
 * @ORM\Entity
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
   * @ORM\ManyToOne(targetEntity="Period")
   * @ORM\JoinColumn(name="period_id", referencedColumnName="id")
   * @Assert\NotBlank()
   * @Assert\Type(type="Gesseh\CoreBundle\Entity\Period")
   */
  private $period;

  /**
   * @ORM\ManyToOne(targetEntity="Student")
   * @ORM\JoinColumn(name="student_id", referencedColumnName="id")
   * @Assert\NotBlank()
   * @Assert\Type(type="Gesseh\UserBundle\Entity\Student")
   */
  private $student;

  /**
   * @ORM\ManyToOne(targetEntity="Department")
   * @ORM\JoinColumn(name="department_id")
   * @Assert\NotBlank()
   * @Assert\Type(type="Gesseh\CoreBundle\Entity\Department")
   */
  private $department;


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
     * Set period
     *
     * @param Gesseh\CoreBundle\Entity\Period $period
     */
    public function setPeriod(\Gesseh\CoreBundle\Entity\Period $period)
    {
        $this->period = $period;
    }

    /**
     * Get period
     *
     * @return Gesseh\CoreBundle\Entity\Period 
     */
    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * Set student
     *
     * @param Gesseh\CoreBundle\Entity\Student $student
     */
    public function setStudent(\Gesseh\CoreBundle\Entity\Student $student)
    {
        $this->student = $student;
    }

    /**
     * Get student
     *
     * @return Gesseh\CoreBundle\Entity\Student 
     */
    public function getStudent()
    {
        return $this->student;
    }

    /**
     * Set department
     *
     * @param Gesseh\CoreBundle\Entity\Department $department
     */
    public function setDepartment(\Gesseh\CoreBundle\Entity\Department $department)
    {
        $this->department = $department;
    }

    /**
     * Get department
     *
     * @return Gesseh\CoreBundle\Entity\Department 
     */
    public function getDepartment()
    {
        return $this->department;
    }
}