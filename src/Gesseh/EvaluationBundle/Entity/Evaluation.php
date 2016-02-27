<?php

/**
 * This file is part of GESSEH project
 *
 * @moderator: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\EvaluationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Gesseh\EvaluationBundle\Entity\Evaluation
 *
 * @ORM\Table(name="evaluation")
 * @ORM\Entity(repositoryClass="Gesseh\EvaluationBundle\Entity\EvaluationRepository")
 */
class Evaluation
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
     * @ORM\ManyToOne(targetEntity="Gesseh\CoreBundle\Entity\Placement")
     * @ORM\JoinColumn(name="placement_id", referencedColumnName="id")
     */
    private $placement;

    /**
     * @ORM\ManyToOne(targetEntity="Gesseh\EvaluationBundle\Entity\EvalCriteria")
     * @ORM\JoinColumn(name="evalcriteria_id", referencedColumnName="id")
     */
    private $evalCriteria;

    /**
     * @var string $value
     *
     * @ORM\Column(name="value", type="string", length=255)
     * @Assert\Range(max = 250)
     */
    private $value;

    /**
     * @var datetime $created_at
     *
     * @ORM\Column(name="created_ad", type="datetime")
     */
    private $created_at;

    /**
     * @var boolean $validated
     *
     * @ORM\Column(name="validated", type="boolean", nullable=false)
     */
    private $validated = false;

    /**
     * @var boolean $moderated
     *
     * @ORM\Column(name="moderated", type="boolean", nullable=false)
     */
    private $moderated = false;

    /**
     * @ORM\ManyToOne(targetEntity="Gesseh\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     */
    private $moderator;

    public function __toString()
    {
      return $this->value;
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
     * Set placement
     *
     * @param Gesseh\CoreBundle\Entity\Placement $placement
     */
    public function setPlacement(\Gesseh\CoreBundle\Entity\Placement $placement)
    {
        $this->placement = $placement;
    }

    /**
     * Get placement
     *
     * @return Gesseh\CoreBundle\Entity\Placement
     */
    public function getPlacement()
    {
        return $this->placement;
    }

    /**
     * Set evalCriteria
     *
     * @param Gesseh\EvaluationBundle\Entity\EvalCriteria $eval_criteria
     */
    public function setEvalCriteria(\Gesseh\EvaluationBundle\Entity\EvalCriteria $eval_criteria)
    {
        $this->evalCriteria = $eval_criteria;
    }

    /**
     * Get evalCriteria
     *
     * @return Gesseh\EvaluationBundle\Entity\EvalCriteria
     */
    public function getEvalCriteria()
    {
        return $this->evalCriteria;
    }

    /**
     * Set value
     *
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
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
     * Set validated
     *
     * @param  boolean    $validated
     * @return Evaluation
     */
    public function setValidated($validated)
    {
        $this->validated = $validated;

        return $this;
    }

    /**
     * Is validated
     *
     * @return boolean
     */
    public function isValidated()
    {
        return $this->validated;
    }

    /**
     * Set moderated
     *
     * @param  boolean    $moderated
     * @return Evaluation
     */
    public function setModerated($moderated)
    {
        $this->moderated = $moderated;

        return $this;
    }

    /**
     * Is moderated
     *
     * @return boolean
     */
    public function isModerated()
    {
        return $this->moderated;
    }

    /**
     * Set moderator
     *
     * @param \Gesseh\UserBundle\Entity\User $moderator
     */
    public function setModerator(\Gesseh\UserBundle\Entity\User $moderator = null)
    {
        $this->moderator = $moderator;
    }

    /**
     * Get moderator
     *
     * @return \Gesseh\UserBundle\Entity\User
     */
    public function getModerator()
    {
        return $this->moderator;
    }
}
