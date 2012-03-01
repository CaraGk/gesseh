<?php

namespace Gesseh\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Gesseh\UserBundle\Entity\Student
 *
 * @ORM\Table(name="student")
 * @ORM\Entity
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
   * @ORM\Column(name="phone", type="string", length="18", nullable=true)
   * @Assert\MinLength(10)
   */
  private $phone;

  /**
   * @ORM\OneToOne(targetEntity="User", cascade={"persist", "remove"})
   * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
   */
  private $user;

  public function __toString()
  {
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
}
