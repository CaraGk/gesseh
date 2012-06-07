<?php

namespace Gesseh\ParameterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Gesseh\ParameterBundle\Entity\Parameter
 *
 * @ORM\Table(name="parameter")
 * @ORM\Entity(repositoryClass="Gesseh\ParameterBundle\Entity\ParameterRepository")
 */
class Parameter
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
     * @ORM\Column(name="name", type="string", length=20)
     * @Assert\NotBlank()
     * @Assert\MinLength(3)
     */
    private $name;

    /**
     * @var string $label
     *
     * @ORM\Column(name="label", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\MinLength(5)
     */
    private $label;

    /**
     * @var string $category
     *
     * @ORM\Column(name="category", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\MinLength(5)
     */
    private $category;

    /**
     * @var smallint $type
     *
     * @ORM\Column(name="type", type="smallint")
     */
    private $type;

    /**
     * @var string $value
     *
     * @ORM\Column(name="value", type="string", length=255)
     */
    private $value;


    public function __toString()
    {
      return $this->label;
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
     * Set category
     *
     * @param string $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * Get category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set type
     *
     * @param smallint $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Get type
     *
     * @return smallint
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set value
     *
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set label
     *
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }
}
