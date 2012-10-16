<?php

namespace Gesseh\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Gesseh\CoreBundle\Entity\Sector
 *
 * @ORM\Table(name="sector")
 * @ORM\Entity(repositoryClass="Gesseh\CoreBundle\Entity\SectorRepository")
 */
class Sector
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
   * @Assert\NotBlank()
   */
  private $name;

  /**
   * @ORM\OneToMany(targetEntity="Department", mappedBy="sector", cascade={"remove", "persist"})
   */
  private $departments;


    public function __construct()
    {
        $this->departments = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add department
     *
     * @param Gesseh\CoreBundle\Entity\Department $department
     */
    public function addDepartment(\Gesseh\CoreBundle\Entity\Department $department)
    {
        $this->departments[] = $department;
    }

    /**
     * Get departments
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getDepartments()
    {
        return $this->departments;
    }
}
