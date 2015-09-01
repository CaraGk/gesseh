<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <caragk@angrand.fr>
 * @copyright: Copyright 2015 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\RegisterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * MemberInfo
 *
 * @ORM\Table(name="member_info")
 * @ORM\Entity(repositoryClass="Gesseh\RegisterBundle\Entity\MemberInfoRepository")
 */
class MemberInfo
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
     * @ORM\ManyToOne(targetEntity="Membership", inversedBy="infos", cascade={"persist"})
     * @ORM\JoinColumn(name="membership_id", referencedColumnName="id")
     */
    private $membershipId;

    /**
     * @ORM\Column(name="question_id", type="integer")
     * @ORM\ManyToOne(targetEntity="MemberQuestion")
     * @ORM\JoinColumn(name="memberquestion_id", referencedColumnName="id")
     */
    private $questionId;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=255)
     */
    private $value;


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
     * Set membershipId
     *
     * @param Gesseh\RegisterBundle\Entity\Membership $membershipId
     * @return MemberInfo
     */
    public function setMembershipId(\Gesseh\RegisterBundle\Entity\Membership $membershipId)
    {
        $this->membershipId = $membershipId;

        return $this;
    }

    /**
     * Get membershipId
     *
     * @return Gesseh\RegisterBundle\Entity\Membership
     */
    public function getMembershipId()
    {
        return $this->membershipId;
    }

    /**
     * Set questionId
     *
     * @param Gesseh\RegisterBundle\Entity\MemberQuestion $questionId
     * @return MemberInfo
     */
    public function setQuestionId(\Gesseh\RegisterBundle\Entity\MemberQuestion $questionId)
    {
        $this->questionId = $questionId;

        return $this;
    }
    /**
     * Get questionId
     *
     * @return Gesseh\RegisterBundle\Entity\MemberQuestion
     */
    public function getQuestionId()
    {
        return $this->questionId;
    }


    /**
     * Set value
     *
     * @param string $value
     * @return MemberInfo
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
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
}
