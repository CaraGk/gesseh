<?php

namespace Gesseh\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Gesseh\CoreBundle\Entity\Sector
 *
 * @ORM\Table(name="sector")
 * @ORM\Entity()
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
     * Add departments
     *
     * @param Gesseh\CoreBundle\Entity\Department $departments
     */
    public function addDepartment(\Gesseh\CoreBundle\Entity\Department $departments)
    {
        $this->departments[] = $departments;
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
