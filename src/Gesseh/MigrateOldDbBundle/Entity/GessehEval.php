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
 * Gesseh\MigrateOldDbBundle\Entity\GessehEval
 *
 * @ORM\Table(name="gesseh_eval")
 * @ORM\Entity
 */
class GessehEval
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
     * @ORM\ManyToOne(targetEntity="GessehStage")
     * @ORM\JoinColumn(name="stage_id", referencedColumnName="id")
     */
    private $stage_id;

    /**
     * @ORM\ManyToOne(targetEntity="GessehCritere")
     * @ORM\JoinColumn(name="critere_id", referencedColumnName="id")
     */
    private $critere_id;

    /**
     * @var string $valeur
     *
     * @ORM\Column(name="valeur", type="string", length=255)
     */
    private $valeur;

    /**
     * @var datetime $created_at
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $created_at;

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
     * Set stage_id
     *
     * @param GessehStage $stageId
     */
    public function setStageId($stageId)
    {
        $this->stage_id = $stageId;
    }

    /**
     * Get stage_id
     *
     * @return GessehStage
     */
    public function getStageId()
    {
        return $this->stage_id;
    }

    /**
     * Set critere_id
     *
     * @param GessehCritere $critereId
     */
    public function setCritereId($critereId)
    {
        $this->critere_id = $critereId;
    }

    /**
     * Get critere_id
     *
     * @return GessehCritere
     */
    public function getCritereId()
    {
        return $this->critere_id;
    }

    /**
     * Set valeur
     *
     * @param string $valeur
     */
    public function setValeur($valeur)
    {
        $this->valeur = $valeur;
    }

    /**
     * Get valeur
     *
     * @return string
     */
    public function getValeur()
    {
        return $this->valeur;
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
}
