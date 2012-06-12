<?php

namespace Gesseh\EvaluationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Query\Expr\OrderBy as OrderBy;

/**
 * Gesseh\EvaluationBundle\Entity\EvalForm
 *
 * @ORM\Table(name="eval_form")
 * @ORM\Entity(repositoryClass="Gesseh\EvaluationBundle\Entity\EvalFormRepository")
 */
class EvalForm
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
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="EvalCriteria", mappedBy="evalform", cascade={"remove", "persist"})
     * @OrderBy({"rank" = "asc"})
     */
    private $criterias;


    public function __construct()
    {
      $this->criterias = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Add criteria
     *
     * @param Gesseh\EvaluationBundle\Entity\EvalCriteria $criteria
     */
    public function addCriteria(\Gesseh\EvaluationBundle\Entity\EvalCriteria $criteria)
    {
      $this->criterias[] = $criteria;
      $criteria->setEvalForm($this);
    }

    /**
     * Set criterias
     *
     * @param Doctrine\Common\Collections\Collection $criterias
     */
    public function setCriterias(\Doctrine\Common\Collections\Collection $criterias)
    {
      $this->criterias = $criterias;
      foreach($criterias as $criteria)
        $criteria->setEvalForm($this);
    }

    /**
     * Get criterias
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getCriterias()
    {
      return $this->criterias;
    }
}
