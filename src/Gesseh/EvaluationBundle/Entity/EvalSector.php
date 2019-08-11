<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\EvaluationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Gesseh\EvaluationBundle\Entity\EvalSector
 *
 * @ORM\Table(name="eval_sector")
 * @ORM\Entity(repositoryClass="Gesseh\EvaluationBundle\Entity\EvalSectorRepository")
 */
class EvalSector
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
     * @ORM\OneToOne(targetEntity="Gesseh\CoreBundle\Entity\Sector")
     * @ORM\JoinColumn(name="sector_id", referencedColumnName="id")
     * @Assert\NotBlank()
     */
    private $sector;

    /**
     * @ORM\ManyToOne(targetEntity="EvalForm")
     * @ORM\JoinColumn(name="form_id", referencedColumnName="id")
     * @Assert\NotBlank()
     */
    private $form;

    /**
     * @ORM\ManyToOne(targetEntity="\Gesseh\RegisterBundle\Entity\Structure", inversedBy="receipts", cascade={"persist"})
     * @ORM\JoinColumn(name="structure_id", referencedColumnName="id")
     *
     * @var Structure $structure
     */
    private $structure;

    public function __toString()
    {
      return $sector . " : " . $form;
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
     * Set sector
     *
     * @param Gesseh\CoreBundle\Entity\Sector $sector
     */
    public function setSector(\Gesseh\CoreBundle\Entity\Sector $sector)
    {
        $this->sector = $sector;
    }

    /**
     * Get sector
     *
     * @return Gesseh\CoreBundle\Entity\Sector
     */
    public function getSector()
    {
        return $this->sector;
    }

    /**
     * Set form
     *
     * @param Gesseh\EvaluationBundle\Entity\EvalForm $form
     */
    public function setForm($form)
    {
        $this->form = $form;
    }

    /**
     * Get form
     *
     * @return Gesseh\EvaluationBundle\Entity\EvalForm
     */
    public function getForm()
    {
        return $this->form;
    }
    /**
     * Set structure
     *
     * @param \Gesseh\RegisterBundle\Entity\Structure $structure
     *
     * @return Fee
     */
    public function setStructure(\Gesseh\RegisterBundle\Entity\Structure $structure = null)
    {
        $this->structure = $structure;

        return $this;
    }

    /**
     * Get structure
     *
     * @return \Gesseh\RegisterBundle\Entity\Structure
     */
    public function getStructure()
    {
        return $this->structure;
    }

}
