<?php

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
     */
    private $value;

    /**
     * @var datetime $created_at
     *
     * @ORM\Column(name="created_ad", type="datetime")
     */
    private $created_at;


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
     * Set eval_criteria
     *
     * @param Gesseh\EvaluationBundle\Entity\EvalCriteria $eval_criteria
     */
    public function setEvalCriteria(\Gesseh\EvaluationBundle\Entity\EvalCriteria $eval_criteria)
    {
        $this->eval_criteria = $eval_criteria;
    }

    /**
     * Get eval_criteria
     *
     * @return Gesseh\EvaluationBundle\Entity\EvalCriteria
     */
    public function getEvalCriteria()
    {
        return $this->eval_criteria;
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
}
