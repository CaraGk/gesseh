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

use Doctrine\ORM\Mapping as ORM,
    Symfony\Component\Validator\Constraints as Assert;

/**
 * Gesseh\CoreBundle\Entity\Accreditation
 *
 * @ORM\Table(name="accreditation")
 * @ORM\Entity(repositoryClass="Gesseh\CoreBundle\Entity\AccreditationRepository")
 */
class Accreditation
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
     * @var string $supervisor
     *
     * @ORM\Column(name="supervisor", type="string", length=100)
     * @Assert\Length(min=5)
     * @Assert\NotBlank()
     */
    private $supervisor;

    /**
     * @ORM\ManyToOne(targetEntity="Department", inversedBy="accreditations", cascade={"persist"})
     * @ORM\JoinColumn(name="department_id", referencedColumnName="id")
     */
    private $department;

    /**
     * @ORM\ManyToOne(targetEntity="Sector", inversedBy="accreditations", cascade={"persist"})
     * @ORM\JoinColumn(name="sector_id", referencedColumnName="id")
     */
    private $sector;

    /**
    * @ORM\OneToOne(targetEntity="Gesseh\UserBundle\Entity\User", cascade={"persist", "remove"})
    * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
    */
    private $user;

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
     * Set supervisor
     *
     * @param string $supervisor
     * @return Accreditation
     */
    public function setSupervisor($supervisor)
    {
        $this->supervisor = $supervisor;

        return $this;
    }

    /**
     * Get supervisor
     *
     * @return string
     */
    public function getSupervisor()
    {
        return $this->supervisor;
    }

    /**
     * Set department
     *
     * @param \Gesseh\CoreBundle\Entity\Department $department
     * @return Accreditation
     */
    public function setDepartment(\Gesseh\CoreBundle\Entity\Department $department = null)
    {
        $this->department = $department;

        return $this;
    }

    /**
     * Get department
     *
     * @return \Gesseh\CoreBundle\Entity\Department
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * Set sector
     *
     * @param \Gesseh\CoreBundle\Entity\Sector $sector
     * @return Accreditation
     */
    public function setSector(\Gesseh\CoreBundle\Entity\Sector $sector = null)
    {
        $this->sector = $sector;

        return $this;
    }

    /**
     * Get sector
     *
     * @return \Gesseh\CoreBundle\Entity\Sector
     */
    public function getSector()
    {
        return $this->sector;
    }

    /**
     * Set user
     *
     * @param \Gesseh\UserBundle\Entity\User $user
     * @return Accreditation
     */
    public function setUser(\Gesseh\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Gesseh\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
