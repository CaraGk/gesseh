<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <caragk@angrand.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Gesseh\CoreBundle\Entity\Department
 *
 * @ORM\Table(name="department")
 * @ORM\Entity(repositoryClass="Gesseh\CoreBundle\Entity\DepartmentRepository")
 */
class Department
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
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\MinLength(5)
     */
    private $name;

    /**
     * @var string $head
     *
     * @ORM\Column(name="head", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $head;

    /**
     * @var text $description
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="Hospital", inversedBy="departments", cascade={"persist"})
     * @ORM\JoinColumn(name="hospital_id", referencedColumnName="id")
     */
    private $hospital;

    /**
     * @ORM\ManyToOne(targetEntity="Sector", inversedBy="departments", cascade={"persist"})
     * @ORM\JoinColumn(name="sector_id", referencedColumnName="id")
     * @Assert\NotBlank()
     * @Assert\Type(type="Gesseh\CoreBundle\Entity\Sector")
     */
    private $sector;

    /**
     * @var smallint $number
     *
     * @ORM\Column(name="number", type="smallint", nullable=true)
     */
    private $number;

    /**
     * @ORM\OneToMany(targetEntity="Placement", mappedBy="department", cascade={"remove"})
     */
    private $placements;


    public function __construct()
    {
      $this->placements = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
      return $this->name . " à " . $this->getHospital();
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
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set head
     *
     * @param string $head
     */
    public function setHead($head)
    {
        $this->head = $head;
    }

    /**
     * Get head
     *
     * @return string
     */
    public function getHead()
    {
        return $this->head;
    }

    /**
     * Set description
     *
     * @param text $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return text
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set hospital
     *
     * @param Gesseh\CoreBundle\Entity\Hospital $hospital
     */
    public function setHospital(\Gesseh\CoreBundle\Entity\Hospital $hospital)
    {
        $this->hospital = $hospital;
    }

    /**
     * Get hospital
     *
     * @return Gesseh\CoreBundle\Entity\Hospital
     */
    public function getHospital()
    {
        return $this->hospital;
    }

    /**
     * Set sector
     *
     * @param Gesseh\CoreBundle\Entity\Sector $sector
     */
    public function setSector(\Gesseh\CoreBundle\Entity\Sector $sector)
    {
        $this->sector = $sector;
        $sector->addDepartment($this);
    }

    /**
     * Get sector
     *
     * @return Gesseh\CoreBundle\Entity\Sector
     */
    public function getSector()
    {
        return $this->sector;
    }

    /**
     * Set number
     *
     * @param smallint $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * Get number
     *
     * @return smallint
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Add placements
     *
     * @param Gesseh\CoreBundle\Entity\Placement $placements
     */
    public function addPlacement(\Gesseh\CoreBundle\Entity\Placement $placements)
    {
        $this->placements[] = $placements;
    }

    /**
     * Remove placements
     *
     * @param Gesseh\CoreBundle\Entity\Placement $placements
     */
    public function removePlacement(\Gesseh\CoreBundle\Entity\Placement $placements)
    {
    }

    /**
     * Get placements
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getPlacements()
    {
        return $this->placements;
    }
}
