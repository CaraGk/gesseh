<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <caragk@angrand.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\MigrateOldDbBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Gesseh\MigrateOldDbBundle\Entity\GessehChoix
 *
 * @ORM\Table(name="gesseh_choix")
 * @ORM\Entity
 */
class GessehChoix
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
     * @ORM\ManyToOne(targetEntity="GessehEtudiant")
     * @ORM\JoinColumn(name="etudiant", referencedColumnName="id")
     */
    private $etudiant;

    /**
     * @ORM\ManyToOne(targetEntity="GessehTerrain")
     * @ORM\JoinColumn(name="poste", referencedColumnName="id")
     */
    private $poste;

    /**
     * @var smallint $ordre
     *
     * @ORM\Column(name="ordre", type="smallint")
     */
    private $ordre;

    /**
     * @var datetime $created_at
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $created_at;

    /**
     * @var datetime $updated_at
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updated_at;

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
     * Set etudiant
     *
     * @param GessehEtudiant $etudiant
     */
    public function setEtudiant($etudiant)
    {
        $this->etudiant = $etudiant;
    }

    /**
     * Get etudiant
     *
     * @return GessehEtudiant
     */
    public function getEtudiant()
    {
        return $this->etudiant;
    }

    /**
     * Set poste
     *
     * @param GessehTerrain $poste
     */
    public function setPoste($poste)
    {
        $this->poste = $poste;
    }

    /**
     * Get poste
     *
     * @return GessehTerrain
     */
    public function getPoste()
    {
        return $this->poste;
    }

    /**
     * Set ordre
     *
     * @param smallint $ordre
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;
    }

    /**
     * Get ordre
     *
     * @return smallint
     */
    public function getOrdre()
    {
        return $this->ordre;
    }

    /**
     * Set created_at
     *
     * @param datetime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;
    }

    /**
     * Get created_at
     *
     * @return datetime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set updated_at
     *
     * @param datetime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;
    }

    /**
     * Get updated_at
     *
     * @return datetime
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }
}
