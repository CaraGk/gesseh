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
     * @ORM\Column(name="nameOB", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min = 5)
     */
    private $name;

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
     * @ORM\OneToMany(targetEntity="Accreditation", mappedBy="department", cascade={"remove", "persist"})
     */
    private $accreditations;

    /**
     * @ORM\OneToMany(targetEntity="Repartition", mappedBy="department", cascade={"remove"})
     */
    private $repartitions;

    public function __construct()
    {
        $this->repartitions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->accreditations = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add Accreditation
     *
     * @param Gesseh\CoreBundle\Entity\Accreditation $accreditation
     * @return Department
     */
    public function addAccreditation(\Gesseh\CoreBundle\Entity\Accreditation $accreditation)
    {
        $this->accreditations[] = $accreditation;

        return $this;
    }

    /**
     * Remove Accreditation
     *
     * @param Gesseh\CoreBundle\Entity\Accreditation $accreditation
     */
    public function removeAccreditation(\Gesseh\CoreBundle\Entity\Accreditation $accreditation)
    {
        $this->accreditations->removeElement($accreditation);
    }

    /**
     * Get Accreditations
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getAccreditations()
    {
        return $this->accreditations;
    }

    /**
     * Add repartition
     *
     * @param Gesseh\CoreBundle\Entity\Repartition $repartition
     */
    public function addRepartition(\Gesseh\CoreBundle\Entity\Repartition $repartition)
    {
        $this->repartitions[] = $repartition;
    }

    /**
     * Remove repartition
     *
     * @param Gesseh\CoreBundle\Entity\Repartition $repartition
     */
    public function removeRepartition(\Gesseh\CoreBundle\Entity\Repartition $repartition)
    {
        $this->repartitions->removeElement($repartition);
    }

    /**
     * Get repartitions
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getRepartitions()
    {
        return $this->repartitions;
    }

    /**
     * Find repartition
     *
     * @return Gesseh\CoreBundle\Entity\Repartition $repartition
     */
    public function findRepartition($period)
    {
        foreach($this->repartitions as $repartition) {
            if($repartition->getPeriod() == $period) {
                return $repartition;
            }
        }
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
