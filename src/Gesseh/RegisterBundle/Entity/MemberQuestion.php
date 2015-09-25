<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2015 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\RegisterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * MemberQuestion
 *
 * @ORM\Table(name="member_question")
 * @ORM\Entity(repositoryClass="Gesseh\RegisterBundle\Entity\MemberQuestionRepository")
 */
class MemberQuestion
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var smallint
     *
     * @ORM\Column(name="type", type="smallint")
     */
    private $type;

    /**
     * @var array
     *
     * @ORM\Column(name="more", type="array", nullable=true)
     */
    private $more;

    /**
     * @var smallint
     *
     * @ORM\Column(name="rank", type="smallint")
     */
    private $rank;

    /**
     * @ORM\OneToMany(targetEntity="MemberInfo", mappedBy="question", cascade={"remove", "persist"}, orphanRemoval=true)
     */
    private $infos;

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
     * @return MemberQuestion
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
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
     * Set type
     *
     * @param smallint $type
     * @return MemberQuestion
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
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
     * Get readable type
     *
     * @return string
     */
    public function getReadableType()
    {
        if ($this->type == 1) {
            return "Choix unique pondéré";
        } elseif ($this->type == 2) {
            return "Texte long";
        } elseif ($this->type == 3) {
            return "Choix multiple";
        } elseif ($this->type == 4) {
            return "Valeur numérique";
        } elseif ($this->type == 5) {
            return "Choix unique non pondéré";
        } elseif ($this->type == 6) {
            return "Horaire";
        } elseif ($this->type == 7) {
            return "Date";
        } elseif ($this->type == 8) {
            return "Menu déroulant";
        } elseif ($this->type == 9) {
            return "Texte court";
        } else {
            return "Type inconnu";
        }
    }

    /**
     * Set more
     *
     * @param array $more
     * @return MemberQuestion
     */
    public function setMore($more)
    {
        $this->more = $more;

        return $this;
    }

    /**
     * Get more
     *
     * @return array
     */
    public function getMore()
    {
        return $this->more;
    }

    /**
     * Set rank
     *
     * @param smallint $rank
     * @return MemberQuestion
     */
    public function setRank($rank)
    {
        $this->rank = $rank;

        return $this;
    }

    /**
     * Get rank
     *
     * @return smallint
     */
    public function getRank()
    {
        return $this->rank;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->infos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add infos
     *
     * @param \Gesseh\RegisterBundle\Entity\MemberInfo $infos
     * @return MemberQuestion
     */
    public function addInfo(\Gesseh\RegisterBundle\Entity\MemberInfo $infos)
    {
        $this->infos[] = $infos;

        return $this;
    }

    /**
     * Remove infos
     *
     * @param \Gesseh\RegisterBundle\Entity\MemberInfo $infos
     */
    public function removeInfo(\Gesseh\RegisterBundle\Entity\MemberInfo $infos)
    {
        $this->infos->removeElement($infos);
    }

    /**
     * Get infos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInfos()
    {
        return $this->infos;
    }
}
