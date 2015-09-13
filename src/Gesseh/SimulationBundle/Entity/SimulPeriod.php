<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\SimulationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Gesseh\SimulationBundle\Entity\SimulPeriod
 *
 * @ORM\Table(name="simul_period")
 * @ORM\Entity(repositoryClass="Gesseh\SimulationBundle\Entity\SimulPeriodRepository")
 */
class SimulPeriod
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
     * @ORM\OneToOne(targetEntity="\Gesseh\CoreBundle\Entity\Period")
     * @ORM\JoinColumn(name="period_id", referencedColumnName="id")
     * @Assert\NotBlank()
     * @Assert\Type(type="\Gesseh\CoreBundle\Entity\Period")
     */
    private $period;

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

    public function __toString()
    {
      return "Simulations du " . $this->begin->format('d-m-Y') . " au " . $this->end->format('d-m-Y');
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
     * Set period
     *
     * @param Gesseh\CoreBundle\Entity\Period $period
     */
    public function setPeriod(\Gesseh\CoreBundle\Entity\Period $period)
    {
        $this->period = $period;
    }

    /**
     * Get period
     *
     * @return Gesseh\CoreBundle\Entity\Period
     */
    public function getPeriod()
    {
        return $this->period;
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
}
