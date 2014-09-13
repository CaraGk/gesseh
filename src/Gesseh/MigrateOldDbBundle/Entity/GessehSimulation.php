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
 * Gesseh\MigrateOldDbBundle\Entity\GessehSimulation
 *
 * @ORM\Table(name="gesseh_simulation")
 * @ORM\Entity
 */
class GessehSimulation
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
     * @var smallint $reste
     *
     * @ORM\Column(name="reste", type="smallint")
     */
    private $reste;

    /**
     * @var smallint $absent
     *
     * @ORM\Column(name="absent", type="smallint")
     */
    private $absent;

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
     * @param GessehEtudian $etudiant
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
     * Set reste
     *
     * @param smallint $reste
     */
    public function setReste($reste)
    {
        $this->reste = $reste;
    }

    /**
     * Get reste
     *
     * @return smallint
     */
    public function getReste()
    {
        return $this->reste;
    }

    /**
     * Set absent
     *
     * @param smallint $absent
     */
    public function setAbsent($absent)
    {
        $this->absent = $absent;
    }

    /**
     * Get absent
     *
     * @return smallint
     */
    public function getAbsent()
    {
        return $this->absent;
    }
}
