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
 * Gesseh\CoreBundle\Entity\Sector
 *
 * @ORM\Table(name="sector")
 * @ORM\Entity(repositoryClass="Gesseh\CoreBundle\Entity\SectorRepository")
 */
class Sector
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
   */
  private $name;

    /**
     * @var boolean $is_default
     *
     * @ORM\Column(name="is_default", type="boolean")
     */
    private $is_default = false;

    /**
     * @ORM\OneToMany(targetEntity="Accreditation", mappedBy="sector", cascade={"remove", "persist"})
     */
    private $accreditations;

    /**
     * @ORM\ManyToOne(targetEntity="\Gesseh\RegisterBundle\Entity\Structure", inversedBy="receipts", cascade={"persist"})
     * @ORM\JoinColumn(name="structure_id", referencedColumnName="id")
     *
     * @var Structure $structure
     */
    private $structure;

    public function __construct()
    {
        $this->accreditations = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
      return $this->name;
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
     * Set is_default
     *
     * @param boolean $is_default
     * @return Sector
     */
    public function setDefault($is_default)
    {
        $this->is_default = $is_default;

        return $this;
    }

    /**
     * Get is_default
     *
     * @return boolean
     */
    public function isDefault()
    {
        return $this->is_default;
    }

    /**
     * Add Accreditation
     *
     * @param Gesseh\CoreBundle\Entity\Accreditation $accreditation
     * @return Sector
     */
    public function addAccreditatiton(\Gesseh\CoreBundle\Entity\Accreditation $accreditation)
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
