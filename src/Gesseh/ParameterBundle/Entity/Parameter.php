<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <caragk@angrand.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\ParameterBundle\Entity;

use KDB\ParametersBundle\Entity\Parameter as BaseParameter;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Gesseh\ParameterBundle\Entity\Parameter
 *
 * @ORM\Table(name="parameter")
 * @ORM\Entity(repositoryClass="Gesseh\ParameterBundle\Entity\ParameterRepository")
 */
class Parameter extends BaseParameter
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
     * @var string $label
     *
     * @ORM\Column(name="label", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min = 5)
     */
    private $label;

    /**
     * @var string $category
     *
     * @ORM\Column(name="category", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min = 5)
     */
    private $category;

    /**
     * @var smallint $type
     *
     * @ORM\Column(name="type", type="smallint")
     */
    private $type;


    public function __construct()
    {
      parent::__construct();
    }

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
     * @return string
     */
    public function getType()
    {
      if ($this->type == 1)
        return 'string';
      elseif ($this->type == 2)
        return 'boolean';
      else
        return $this->type;
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
