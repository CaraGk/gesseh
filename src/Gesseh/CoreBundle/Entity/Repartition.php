<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2016 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Gesseh\CoreBundle\Entity\Repartition
 *
 * @ORM\Table(name="repartition")
 * @ORM\Entity(repositoryClass="Gesseh\CoreBundle\Entity\RepartitionRepository")
 */
class Repartition
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
     * @ORM\ManyToOne(targetEntity="Period", inversedBy="repartitions", cascade={"persist"})
     * @ORM\JoinColumn(name="period_id", referencedColumnName="id")
     * @Assert\NotBlank()
     * @Assert\Type(type="Gesseh\CoreBundle\Entity\Period")
     */
    private $period;

    /**
     * @ORM\ManyToOne(targetEntity="Department", inversedBy="repartitions", cascade={"persist"})
     * @ORM\JoinColumn(name="department_id", referencedColumnName="id")
     * @Assert\NotBlank()
     * @Assert\Type(type="Gesseh\CoreBundle\Entity\Department")
     */
    private $department;

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

    /**
     * @ORM\Column(name="cluster", type="string", length=100, nullable=true)
     */
    private $cluster;

    public function __toString()
    {
        return $this->department->getName() . " à " . $this->department->getHospital()->getName() . " (" . $this->period->getBegin()->format('d/m/Y') . "-" . $this->period->getEnd()->format('d/m/Y') . ")";
    }
    public function __construct()
    {
      $this->placements = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set department
     *
     * @param Gesseh\CoreBundle\Entity\Department $department
     */
    public function setDepartment(\Gesseh\CoreBundle\Entity\Department $department)
    {
        $this->department = $department;
        $department->addRepartition($this);
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
     * Add placement
     *
     * @param Gesseh\CoreBundle\Entity\Placement $placement
     */
    public function addPlacement(\Gesseh\CoreBundle\Entity\Placement $placement)
    {
        $this->placements[] = $placement;
    }

    /**
     * Remove placement
     *
     * @param Gesseh\CoreBundle\Entity\Placement $placement
     */
    public function removePlacement(\Gesseh\CoreBundle\Entity\Placement $placement)
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

    /**
     * Set cluster
     *
     * @param  string     $cluster
     * @return Department
     */
    public function setCluster($cluster)
    {
        $this->cluster = $cluster;

        return $this;
    }

    /**
     * Get cluster
     *
     * @return string
     */
    public function getCluster()
    {
        return $this->cluster;
    }
}
