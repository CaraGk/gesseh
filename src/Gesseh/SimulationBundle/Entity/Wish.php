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

/**
 * Gesseh\SimulationBundle\Entity\Wish
 *
 * @ORM\Table(name="wish")
 * @ORM\Entity(repositoryClass="Gesseh\SimulationBundle\Entity\WishRepository")
 */
class Wish
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
     * @ORM\ManyToOne(targetEntity="Gesseh\CoreBundle\Entity\Department")
     * @ORM\JoinColumn(name="department", referencedColumnName="id")
     * @Assert\NotBlank()
     * @Assert\Type(type="Gesseh\CoreBundle\Entity\Department")
     */
    private $department;

    /**
     * @var integer $rank
     *
     * @ORM\Column(name="rank", type="integer")
     */
    private $rank;

    /**
     * @ORM\ManyToOne(targetEntity="Gesseh\SimulationBundle\Entity\Simulation", inversedBy="wishes", cascade={"persist"})
     * @ORM\JoinColumn(name="sim_id", referencedColumnName="id", nullable=false)
     * @Assert\Type(type="Gesseh\SimulationBundle\Entity\Simulation")
     */
    private $simulation;

    /**
     * @ORM\ManyToOne(targetEntity="\Gesseh\RegisterBundle\Entity\Structure", inversedBy="receipts", cascade={"persist"})
     * @ORM\JoinColumn(name="structure_id", referencedColumnName="id")
     *
     * @var Structure $structure
     */
    private $structure;

    public function __toString()
    {
      return $this->department;
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

    /**
     * Set rank
     *
     * @param integer $rank
     */
    public function setRank($rank)
    {
        $this->rank = $rank;
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
     * Set simulation
     *
     * @param Gesseh\SimulationBundle\Entity\Simulation $simulation
     */
    public function setSimulation(\Gesseh\SimulationBundle\Entity\Simulation $simulation)
    {
      $this->simulation = $simulation;
    }

    /**
     * Get simulation
     *
     * @return Gesseh\SimulationBundle\Entity\Simulation
     */
    public function getSimulation()
    {
      return $this->simulation;
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
