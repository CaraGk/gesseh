<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\MigrateOldDbBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Gesseh\MigrateOldDbBundle\Entity\GessehStage
 *
 * @ORM\Table(name="gesseh_stage")
 * @ORM\Entity
 */
class GessehStage
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
     * @ORM\ManyToOne(targetEntity="GessehTerrain")
     * @ORM\JoinColumn(name="terrain_id", referencedColumnName="id")
     */
    private $terrain_id;

    /**
     * @ORM\ManyToOne(targetEntity="GessehPeriode")
     * @ORM\JoinColumn(name="periode_id", referencedColumnName="id")
     */
    private $periode_id;

    /**
     * @ORM\ManyToOne(targetEntity="GessehEtudiant")
     * @ORM\JoinColumn(name="etudiant_id", referencedColumnName="id")
     */
    private $etudiant_id;

    /**
     * @ORM\ManyToOne(targetEntity="GessehFormEval")
     * @ORM\JoinColumn(name="form", referencedColumnName="id")
     */
    private $form;

    /**
     * @var smallint $is_active
     *
     * @ORM\Column(name="is_active", type="smallint")
     */
    private $is_active;

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
     * Set terrain_id
     *
     * @param GessehTerrain $terrainId
     */
    public function setTerrainId($terrainId)
    {
        $this->terrain_id = $terrainId;
    }

    /**
     * Get terrain_id
     *
     * @return GessehTerrain
     */
    public function getTerrainId()
    {
        return $this->terrain_id;
    }

    /**
     * Set periode_id
     *
     * @param GessehPeriode $periodeId
     */
    public function setPeriodeId($periodeId)
    {
        $this->periode_id = $periodeId;
    }

    /**
     * Get periode_id
     *
     * @return GessehPeriode
     */
    public function getPeriodeId()
    {
        return $this->periode_id;
    }

    /**
     * Set etudiant_id
     *
     * @param GessehEtudiant $etudiantId
     */
    public function setEtudiantId($etudiantId)
    {
        $this->etudiant_id = $etudiantId;
    }

    /**
     * Get etudiant_id
     *
     * @return GessehEtudiant
     */
    public function getEtudiantId()
    {
        return $this->etudiant_id;
    }

    /**
     * Set form
     *
     * @param GessehFormEval $form
     */
    public function setForm($form)
    {
        $this->form = $form;
    }

    /**
     * Get form
     *
     * @return GessehFormEval
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * Set is_active
     *
     * @param smallint $isActive
     */
    public function setIsActive($isActive)
    {
        $this->is_active = $isActive;
    }

    /**
     * Get is_active
     *
     * @return smallint
     */
    public function getIsActive()
    {
        return $this->is_active;
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
