<?php

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
   * @var string $surname
   *
   * @ORM\Column(name="surname", type="string", length=255)
   * @Assert\NotBlank()
   * @Assert\MinLength(2)
   */
  private $surname;

  /**
   * @var string $name
   *
   * @ORM\Column(name="name", type="string", length=255)
   * @Assert\NotBlank()
   * @Assert\MinLength(2)
   */
  private $name;

  /**
   * @var string $phone
   *
   * @ORM\Column(name="phone", type="string", length=18, nullable=true)
   * @Assert\MinLength(10)
   */
  private $phone;

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
   * @ORM\Column(name="anonymous", type="boolean")
   */
  private $anonymous;


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
}
