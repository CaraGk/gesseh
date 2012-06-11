<?php

namespace Gesseh\SimulationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Gesseh\SimulationBundle\Entity\Simulation
 *
 * @ORM\Table(name="simulation")
 * @ORM\Entity(repositoryClass="Gesseh\SimulationBundle\Entity\SimulationRepository")
 */
class Simulation
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Gesseh\UserBundle\Entity\Student")
     * @ORM\JoinColumn(name="student_id", referencedColumnName="id", nullable=false)
     * @Assert\NotBlank()
     * @Assert\Type(type="Gesseh\UserBundle\Entity\Student")
     */
    private $student;

    /**
     * @ORM\ManyToOne(targetEntity="Gesseh\CoreBundle\Entity\Department")
     * @ORM\JoinColumn(name="department", referencedColumnName="id", nullable=true)
     */
    private $department;

    /**
     * @var smallint $extra
     *
     * @ORM\Column(name="extra", type="smallint", nullable=true)
     */
    private $extra;

    /**
     * @var boolean $active
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @ORM\OneToMany(targetEntity="Gesseh\SimulationBundle\Entity\Wish", mappedBy="simstudent", cascade={"remove"})
     */
    private $wishes;


    public function __construct()
    {
      $this->wishes = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set id
     *
     * @param integer
     */
    public function setId($id)
    {
      $this->id = $id;
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
     * Set department
     *
     * @param Gesseh\CoreBundle\Entity\Department $department
     */
    public function setDepartment($department)
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

    /**
     * Set extra
     *
     * @param smallint $extra
     */
    public function setExtra($extra)
    {
        $this->extra = $extra;
    }

    /**
     * Get extra
     *
     * @return smallint
     */
    public function getExtra()
    {
        return $this->extra;
    }

    /**
     * Set active
     *
     * @param boolean $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set wishes
     *
     * @param Doctrine\Common\Collections\Collection $wishes
     */
    public function setWishes(\Doctrine\Common\Collections\Collection $wishes)
    {
      $this->wishes = $wishes;
    }

    /**
     * Get wishes
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getWishes()
    {
      return $this->wishes;
    }

    /**
     * Count wishes
     *
     * @return integer
     */
    public function countWishes()
    {
      return count($this->wishes);
    }
}
