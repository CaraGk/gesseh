<?php

namespace Gesseh\SimulationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Gesseh\SimulationBundle\Entity\SectorRule
 *
 * @ORM\Table(name="sector_rule")
 * @ORM\Entity(repositoryClass="Gesseh\SimulationBundle\Entity\SectorRuleRepository")
 */
class SectorRule
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
     * @ORM\ManyToOne(targetEntity="Gesseh\UserBundle\Entity\Grade")
     * @ORM\JoinColumn(name="grade_id", referencedColumnName="id")
     * @Assert\NotBlank()
     * @Assert\Type(type="Gesseh\UserBundle\Entity\Grade")
     */
    private $grade;

    /**
     * @ORM\ManyToOne(targetEntity="Gesseh\CoreBundle\Entity\Sector")
     * @ORM\JoinColumn(name="sector", referencedColumnName="id")
     * @Assert\NotBlank()
     * @Assert\Type(type="Gesseh\CoreBundle\Entity\Sector")
     */
    private $sector;

    /**
     * @var string $relation
     *
     * @ORM\Column(name="relation", type="string", length=10)
     * @Assert\NotBlank()
     */
    private $relation;

    
    public function __toString()
    {
      return '(' . $this->grade . ' ' . $this->relation . ' ' . $this->sector . ')';
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
     * Set grade
     *
     * @param Gesseh\UserBundle\Entity\Grade $grade
     */
    public function setGrade(\Gesseh\UserBundle\Entity\Grade $grade)
    {
        $this->grade = $grade;
    }

    /**
     * Get grade
     *
     * @return Gesseh\UserBundle\Entity\Grade
     */
    public function getGrade()
    {
        return $this->grade;
    }

    /**
     * Set sector
     *
     * @param Gesseh\CoreBundle\Entity\Sector $sector
     */
    public function setSector(\Gesseh\CoreBundle\Entity\Sector $sector)
    {
        $this->sector = $sector;
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
     * Set relation
     *
     * @param string $relation
     */
    public function setRelation($relation)
    {
        $this->relation = $relation;
    }

    /**
     * Get relation
     *
     * @return string
     */
    public function getRelation()
    {
        return $this->relation;
    }
}
