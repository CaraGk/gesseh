<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <caragk@angrand.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
    return "Du " . $this->begin->format('d-m-Y') . " au " . $this->end->format('d-m-Y');
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
