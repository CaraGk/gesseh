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
     * @ORM\ManyToOne(targetEntity="Gesseh\CoreBundle\Entity\Placement", cascade={"remove"})
     * @ORM\JoinColumn(name="placement_id", referencedColumnName="id")
     */
    private $placement;

    /**
     * @ORM\ManyToOne(targetEntity="Gesseh\EvaluationBundle\Entity\EvalCriteria", cascade={"remove"})
     * @ORM\JoinColumn(name="evalcriteria_id", referencedColumnName="id")
     */
    private $evalcriteria;

    /**
     * @var string $value
     *
     * @ORM\Column(name="value", type="string", length=255)
     */
    private $value;


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
     * Set evalcriteria
     *
     * @param Gesseh\EvaluationBundle\Entity\EvalCriteria $evalcriteria
     */
    public function setEvalCriteria(\Gesseh\EvaluationBundle\Entity\EvalCriteria $evalcriteria)
    {
        $this->evalcriteria = $evalcriteria;
    }

    /**
     * Get evalcriteria
     *
     * @return Gesseh\EvaluationBundle\Entity\EvalCriteria
     */
    public function getEvalcriteria()
    {
        return $this->evalcriteria;
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
}
