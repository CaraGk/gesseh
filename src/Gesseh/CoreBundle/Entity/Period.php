<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2013-2016 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Gesseh\CoreBundle\Entity\Period
 *
 * @ORM\Table(name="period")
 * @ORM\Entity(repositoryClass="Gesseh\CoreBundle\Entity\PeriodRepository")
 */
class Period
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
     * @var text $name
     *
     * @ORM\Column(name="name", type="string", length=30)
     */
    private $name;

    /**
     * @var date $begin
     *
     * @ORM\Column(name="begin", type="date")
     */
    private $begin;

    /**
     * @var date $end
     *
     * @ORM\Column(name="end", type="date")
     */
    private $end;

    /**
     * @ORM\OneToMany(targetEntity="Repartition", mappedBy="period", cascade={"remove"})
     */
    private $repartitions;

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
     * Set begin
     *
     * @param date $begin
     */
    public function setBegin($begin)
    {
        $this->begin = $begin;
    }

    /**
     * Get begin
     *
     * @return date
     */
    public function getBegin()
    {
        return $this->begin;
    }

    /**
     * Set end
     *
     * @param date $end
     */
    public function setEnd($end)
    {
        $this->end = $end;
    }

    /**
     * Get end
     *
     * @return date
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Add repartitions
     *
     * @param Gesseh\CoreBundle\Entity/Repartition $repartition
     */
    public function addRepartition(\Gesseh\CoreBundle\Entity\Repartition $repartition)
    {
        $this->repartitions[] = $repartition;
    }

    /**
     * Remove repartition
     *
     * @param Gesseh\CoreBundle\Entity\Repartition $repartition
     */
    public function removeRepartition(\Gesseh\CoreBundle\Entity\Repartition $repartition)
    {
        $this->repartitions->removeElement($repartition);
    }

    /**
     * Get repartitions
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getRepartitions()
    {
        return $this->repartitions;
    }
}
