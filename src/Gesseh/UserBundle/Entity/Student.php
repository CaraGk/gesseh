<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Gesseh\UserBundle\Entity\Student
 *
 * @ORM\Table(name="student")
 * @ORM\Entity(repositoryClass="Gesseh\UserBundle\Entity\StudentRepository")
 */
class Student
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
   * @var string $title
   *
   * @ORM\Column(name="title", type="string", length=5)
   */
  private $title;

  /**
   * @var string $surname
   *
   * @ORM\Column(name="surname", type="string", length=255)
   * @Assert\NotBlank()
   * @Assert\Length(min = 2)
   */
  private $surname;

  /**
   * @var string $name
   *
   * @ORM\Column(name="name", type="string", length=255)
   * @Assert\NotBlank()
   * @Assert\Length(min = 2)
   */
  private $name;

  /**
   * @var date $birthday
   *
   * @ORM\Column(name="birthday", type="date")
   */
  private $birthday;

  /**
   * @var string $birthplace
   *
   * @ORM\Column(name="birthplace", type="string")
   */
  private $birthplace;

  /**
   * @var string $phone
   *
   * @ORM\Column(name="phone", type="string", length=18, nullable=true)
   * @Assert\Length(min = 10)
   */
  private $phone;

  /**
   * @var array $address
   *
   * @ORM\Column(name="address", type="array", nullable=true)
   */
  private $address;

  /**
   * @ORM\OneToOne(targetEntity="User", cascade={"persist", "remove"})
   * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
   */
  private $user;

  /**
   * @ORM\ManyToOne(targetEntity="Grade", inversedBy="students")
   * @ORM\JoinColumn(name="grade_id", referencedColumnName="id")
   */
  private $grade;

  /**
   * @var smallint $ranking
   *
   * @ORM\Column(name="ranking", type="smallint", nullable=true)
   */
  private $ranking;

  /**
   * @var smallint $graduate
   *
   * @ORM\Column(name="graduate", type="smallint", nullable=true)
   */
  private $graduate;

  /**
   * @var boolean $anonymous
   *
   * @ORM\Column(name="anonymous", type="boolean", nullable=true)
   */
  private $anonymous;

  /**
   * @ORM\OneToMany(targetEntity="Gesseh\CoreBundle\Entity\Placement", mappedBy="student", cascade={"remove", "persist"}, orphanRemoval=true)
   */
  private $placements;

  public function __toString()
  {
    if ($this->isAnonymous())
      return "*** anonyme ***";
    else
      return $this->name . ' ' . $this->surname;
  }

    /**
     * Set surname
     *
     * @param string $surname
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
    }

    /**
     * Get surname
     *
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Get anonymized surname
     * @return string
     */
    public function getAnonSurname()
    {
      if ($this->isAnonymous())
        return "***";
      else
        return $this->surname;
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
     * Get anonymized name
     *
     * @return string
     */
    public function getAnonName()
    {
      if ($this->isAnonymous())
        return "***";
      else
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
     * Set phone
     *
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set user
     *
     * @param Gesseh\UserBundle\Entity\User $user
     */
    public function setUser(\Gesseh\UserBundle\Entity\User $user)
    {
        $this->user = $user;
    }

    /**
     * Get user
     *
     * @return Gesseh\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set grade
     *
     * @param Gesseh\UserBundle\Entity\Grade $grade
     */
    public function setGrade(\Gesseh\UserBundle\Entity\Grade $grade)
    {
        $this->grade = $grade;
    }

    /**
     * Get grade
     *
     * @return Gesseh\UserBundle\Entity\Grade
     */
    public function getGrade()
    {
        return $this->grade;
    }

    /**
     * Set ranking
     *
     * @param smallint $ranking
     */
    public function setRanking($ranking)
    {
        $this->ranking = $ranking;
    }

    /**
     * Get ranking
     *
     * @return smallint
     */
    public function getRanking()
    {
        return $this->ranking;
    }

    /**
     * Set graduate
     *
     * @param smallint $graduate
     */
    public function setGraduate($graduate)
    {
        $this->graduate = $graduate;
    }

    /**
     * Get graduate
     *
     * @return smallint
     */
    public function getGraduate()
    {
        return $this->graduate;
    }

    /**
     * Get anonymous
     *
     * @return boolean
     */
    public function getAnonymous()
    {
      return $this->anonymous;
    }

    /**
     * Is anonymous?
     *
     * @return boolean
     */
    public function isAnonymous()
    {
      if ($this->anonymous)
        return true;
      else
        return false;
    }

    /**
     * Set anonymous
     *
     * @param boolean
     */
    public function setAnonymous($anonymous)
    {
      $this->anonymous = $anonymous;
    }

    /**
     * Set address
     *
     * @param  string  $address
     * @return Student
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }
    /**
     * Add placements
     *
     * @param Gesseh\CoreBundle\Entity\Placement $placement
     */
    public function addPlacement(\Gesseh\CoreBundle\Entity\Placement $placement)
    {
        $this->placements[] = $placement;
        $placement->setStudent($this);
    }

    /**
     * Remove placements
     *
     * @param Gesseh\CoreBundle\Entity\Placement $placement
     */
    public function removePlacement(\Gesseh\CoreBundle\Entity\Placement $placement)
    {
    }

    /**
     * Set placements
     *
     * @param Doctrine\Common\Collections\Collection $placements
     */
    public function setPlacements(\Doctrine\Common\Collections\Collection $placements)
    {
      $this->placements = $placements;
      foreach($placements as $placement)
        $placement->setStudent($this);
    }

    /**
     * Get placements
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getPlacements()
    {
        return $this->placements;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->placements = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set title
     *
     * @param $title
     * @return Student
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set birthday
     *
     * @param $birthday
     * @return Student
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday
     *
     * @return $birthday
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set birthplace
     *
     * @param $birthplace
     * @return Student
     */
    public function setBirthplace($birthplace)
    {
        $this->birthplace = $birthplace;

        return $this;
    }

    /**
     * Get birthplace
     *
     * @return $birthplace
     */
    public function getBirthplace()
    {
        return $this->birthplace;
    }

}
