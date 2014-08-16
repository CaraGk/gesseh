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
     * @ORM\OneToMany(targetEntity="EvalCriteria", mappedBy="eval_form", cascade={"remove", "persist"}, orphanRemoval=true)
     * @ORM\OrderBy({"rank" = "asc"})
     */
    private $criterias;

    /**
     * @ORM\OneToMany(targetEntity="EvalSector", mappedBy="form", cascade={"remove"})
     */
    private $sectors;


    public function __construct()
    {
      $this->criterias = new \Doctrine\Common\Collections\ArrayCollection();
      $this->sectors = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Remove criteria
     *
     * @param Gesseh\EvaluationBundle\Entity\EvalCriteria $criteria
     */
    public function removeCriteria(\Gesseh\EvaluationBundle\Entity\EvalCriteria $criteria)
    {
        $this->criterias = array_diff($this->criterias, array($criteria));
        $criteria->setEvalForm(null);
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

    /**
     * Add criterias
     *
     * @param Gesseh\EvaluationBundle\Entity\EvalCriteria $criterias
     */
    public function addEvalCriteria(\Gesseh\EvaluationBundle\Entity\EvalCriteria $criterias)
    {
        $this->criterias[] = $criterias;
    }

    /**
     * Add sectors
     *
     * @param Gesseh\EvaluationBundle\Entity\EvalSector $sectors
     */
    public function addEvalSector(\Gesseh\EvaluationBundle\Entity\EvalSector $sectors)
    {
        $this->sectors[] = $sectors;
    }

    /**
     * Get sectors
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getSectors()
    {
        return $this->sectors;
    }

    /**
     * Add sectors
     *
     * @param \Gesseh\EvaluationBundle\Entity\EvalSector $sectors
     * @return EvalForm
     */
    public function addSector(\Gesseh\EvaluationBundle\Entity\EvalSector $sectors)
    {
        $this->sectors[] = $sectors;

        return $this;
    }

    /**
     * Remove sectors
     *
     * @param \Gesseh\EvaluationBundle\Entity\EvalSector $sectors
     */
    public function removeSector(\Gesseh\EvaluationBundle\Entity\EvalSector $sectors)
    {
        $this->sectors->removeElement($sectors);
    }
}
