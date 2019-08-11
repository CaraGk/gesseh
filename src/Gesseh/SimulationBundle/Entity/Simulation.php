<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\SimulationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Query\Expr\OrderBy as OrderBy;

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
     * @var int $rank
     *
     * @ORM\Column(name="rank", type="integer")
     */
    private $rank;

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
     * var $is_excess
     * @ORM\Column(name="excess", type="boolean", nullable=true)
     */
    private $is_excess;

    /**
     * var $is_validated;
     *
     * @ORM\Column(name="validated", type="boolean", nullable=true)
     */
    private $is_validated;

    /**
     * @ORM\OneToMany(targetEntity="Gesseh\SimulationBundle\Entity\Wish", mappedBy="simstudent", cascade={"remove"})
     * @ORM\OrderBy({"rank" = "asc"})
     */
    private $wishes;

    /**
     * @ORM\ManyToOne(targetEntity="\Gesseh\RegisterBundle\Entity\Structure", inversedBy="receipts", cascade={"persist"})
     * @ORM\JoinColumn(name="structure_id", referencedColumnName="id")
     *
     * @var Structure $structure
     */
    private $structure;

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
     * Get rank
     *
     * @return integer
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * Set rank
     *
     * @param integer $rank
     * @return Simulation
     */
    public function setRank($rank)
    {
        $this->rank = $rank;

        return $this;
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
     * Set is_excess
     *
     * @param boolean $is_excess
     * @return Simulation
     */
    public function setIsExcess($is_excess)
    {
        $this->is_excess = $is_excess;

        return $this;
    }

    /**
     * Is excess?
     *
     * @return boolean
     */
    public function isExcess()
    {
        return $this->is_excess;
    }

    /**
     * Set is_validated
     *
     * @param boolean $is_validated
     * @return Simulation
     */
    public function setIsValidated($is_validated)
    {
        $this->is_validated = $is_validated;

        return $this;
    }

    /**
     * Is validated?
     *
     * @return boolean
     */
    public function isValidated()
    {
        return $this->is_validated;
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

    /**
     * Add wishes
     *
     * @param Gesseh\SimulationBundle\Entity\Wish $wishes
     */
    public function addWish(\Gesseh\SimulationBundle\Entity\Wish $wishes)
    {
        $this->wishes[] = $wishes;
    }

    /**
     * Remove wishes
     *
     * @param Gesseh\SimulationBundle\Entity\Wish $wishes
     */
    public function removeWish(\Gesseh\SimulationBundle\Entity\Wish $wishes)
    {
    }
    /**
     * Set structure
     *
     * @param \Gesseh\RegisterBundle\Entity\Structure $structure
     *
     * @return Fee
     */
    public function setStructure(\Gesseh\RegisterBundle\Entity\Structure $structure = null)
    {
        $this->structure = $structure;

        return $this;
    }

    /**
     * Get structure
     *
     * @return \Gesseh\RegisterBundle\Entity\Structure
     */
    public function getStructure()
    {
        return $this->structure;
    }

}
