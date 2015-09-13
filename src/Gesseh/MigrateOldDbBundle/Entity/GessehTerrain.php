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
 * Gesseh\MigrateOldDbBundle\Entity\GessehTerrain
 *
 * @ORM\Table(name="gesseh_terrain")
 * @ORM\Entity
 */
class GessehTerrain
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
     * @ORM\ManyToOne(targetEntity="GessehHopital")
     * @ORM\JoinColumn(name="hopital_id", referencedColumnName="id")
     */
    private $hopital_id;

    /**
     * @var string $titre
     *
     * @ORM\Column(name="titre", type="string", length=100)
     */
    private $titre;

    /**
     * @ORM\ManyToOne(targetEntity="GessehFiliere")
     * @ORM\JoinColumn(name="filiere", referencedColumnName="id")
     */
    private $filiere;

    /**
     * @var string $patron
     *
     * @ORM\Column(name="patron", type="string", length=50)
     */
    private $patron;

    /**
     * @var integer $total
     *
     * @ORM\Column(name="total", type="integer")
     */
    private $total;

    /**
     * @var longblob $page
     *
     * @ORM\Column(name="page", type="longblob")
     */
    private $page;

    /**
     * @var integer $is_active
     *
     * @ORM\Column(name="is_active", type="integer")
     */
    private $is_active;

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
     * Set hopital_id
     *
     * @param GessehHopital $hopitalId
     */
    public function setHopitalId($hopitalId)
    {
        $this->hopital_id = $hopitalId;
    }

    /**
     * Get hopital_id
     *
     * @return GessehHopital
     */
    public function getHopitalId()
    {
        return $this->hopital_id;
    }

    /**
     * Set titre
     *
     * @param string $titre
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set filiere
     *
     * @param GessehFiliere $filiere
     */
    public function setFiliere($filiere)
    {
        $this->filiere = $filiere;
    }

    /**
     * Get filiere
     *
     * @return GessehFiliere
     */
    public function getFiliere()
    {
        return $this->filiere;
    }

    /**
     * Set patron
     *
     * @param string $patron
     */
    public function setPatron($patron)
    {
        $this->patron = $patron;
    }

    /**
     * Get patron
     *
     * @return string
     */
    public function getPatron()
    {
        return $this->patron;
    }

    /**
     * Set total
     *
     * @param integer $total
     */
    public function setTotal($total)
    {
        $this->total = $total;
    }

    /**
     * Get total
     *
     * @return integer
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set page
     *
     * @param longblob $page
     */
    public function setPage($page)
    {
        $this->page = $page;
    }

    /**
     * Get page
     *
     * @return longblob
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set is_active
     *
     * @param integer $isActive
     */
    public function setIsActive($isActive)
    {
        $this->is_active = $isActive;
    }

    /**
     * Get is_active
     *
     * @return integer
     */
    public function getIsActive()
    {
        return $this->is_active;
    }
}
