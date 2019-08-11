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
     * @var date $begin
     *
     * @ORM\Column(name="begin", type="date")
     */
    private $begin;

    /**
     * @var date $end
     *
     * @ORM\Column(name="end", type="date")
     */
    private $end;

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
     * @ORM\ManyToOne(targetEntity="Gesseh\UserBundle\Entity\User", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var text $comment
     *
     * @ORM\Column(name="comment", type="text", nullable=true)
     */
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity="\Gesseh\RegisterBundle\Entity\Structure", inversedBy="receipts", cascade={"persist"})
     * @ORM\JoinColumn(name="structure_id", referencedColumnName="id")
     *
     * @var Structure $structure
     */
    private $structure;

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
     * Set begin
     *
     * @param \DateTime $begin
     * @return Accreditation
     */
    public function setBegin($begin)
    {
        $this->begin = $begin;

        return $this;
    }

    /**
     * Get begin
     *
     * @return \DateTime
     */
    public function getBegin()
    {
        return $this->begin;
    }

    /**
     * Set end
     *
     * @param \DateTime $end
     * @return Accreditation
     */
    public function setEnd($end)
    {
        $this->end = $end;

        return $this;
    }

    /**
     * Get end
     *
     * @return \DateTime
     */
    public function getEnd()
    {
        return $this->end;
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

    /**
     * Set comment
     *
     * @param string $comment
     * @return Accreditation
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
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
