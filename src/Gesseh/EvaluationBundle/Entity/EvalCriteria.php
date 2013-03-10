<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <caragk@angrand.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\EvaluationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Gesseh\EvaluationBundle\Entity\EvalCriteria
 *
 * @ORM\Table(name="eval_criteria")
 * @ORM\Entity(repositoryClass="Gesseh\EvaluationBundle\Entity\EvalCriteriaRepository")
 */
class EvalCriteria
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
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\MinLength(5)
     */
    private $name;

    /**
     * @var smallint $type
     *
     * @ORM\Column(name="type", type="smallint")
     * @Assert\NotBlank()
     */
    private $type;

    /**
     * @var string $more
     *
     * @ORM\Column(name="more", type="string", length=255, nullable=true)
     */
    private $more;

    /**
     * @var smallint $rank
     *
     * @ORM\Column(name="rank", type="smallint")
     */
    private $rank;

    /**
     * @ORM\ManyToOne(targetEntity="EvalForm", inversedBy="criterias", cascade={"persist"})
     * @ORM\JoinColumn(name="evalform_id", referencedColumnName="id")
     */
    private $eval_form;


    public function __toString()
    {
      return $this->name;
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
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set type
     *
     * @param smallint $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Get type
     *
     * @return smallint
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get readable type
     *
     * @return string
     */
    public function getReadableType()
    {
      if($this->type == 1)
        return 'Bouton radio';
      elseif($this->type == 2)
        return 'Texte long';
      else
        return $this->type;
    }

    /**
     * Set more
     *
     * @param string $more
     */
    public function setMore($more)
    {
        $this->more = $more;
    }

    /**
     * Get more
     *
     * @return string
     */
    public function getMore()
    {
        return $this->more;
    }

    /**
     * Set rank
     *
     * @param smallint $rank
     */
    public function setRank($rank)
    {
        $this->rank = $rank;
    }

    /**
     * Get rank
     *
     * @return smallint
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * Set eval_form
     *
     * @param Gesseh\EvaluationBundle\Entity\EvalForm $eval_form
     */
    public function setEvalForm(\Gesseh\EvaluationBundle\Entity\EvalForm $eval_form)
    {
        $this->eval_form = $eval_form;
    }

    /**
     * Get evalform
     *
     * @return Gesseh\EvaluationBundle\Entity\EvalForm
     */
    public function getEvalForm()
    {
        return $this->evalform;
    }
}
