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
   * @var date $from
   *
   * @ORM\Column(name="from", type="date")
   */
  private $from;

  /**
   * @var date $to
   *
   * @ORM\Column(name="to", type="date")
   */
  private $to;

  public function __toString()
  {
    return "Du " . $this->from . " au " . $this->to;
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
     * Set from
     *
     * @param date $from
     */
    public function setFrom($from)
    {
        $this->from = $from;
    }

    /**
     * Get from
     *
     * @return date 
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Set to
     *
     * @param date $to
     */
    public function setTo($to)
    {
        $this->to = $to;
    }

    /**
     * Get to
     *
     * @return date 
     */
    public function getTo()
    {
        return $this->to;
    }
}