<?php

namespace Gesseh\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Gesseh\CoreBundle\Entity\Period
 *
 * @ORM\Table(name="period")
 * @ORM\Entity
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
