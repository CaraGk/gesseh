<?php

/**
 * This file is part of PIGASS project
 *
 * @author: Pierre-François ANGRAND <pigass@medlibre.fr>
 * @copyright: Copyright 2015-2018 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\RegisterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Membership
 *
 * @ORM\Table(name="membership")
 * @ORM\Entity(repositoryClass="Gesseh\RegisterBundle\Entity\MembershipRepository")
 */
class Membership
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
     * @ORM\ManyToOne(targetEntity="\Gesseh\UserBundle\Entity\Person", inversedBy="memberships", cascade={"persist"})
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     */
    private $person;

    /**
     * @var integer
     *
     * @ORM\Column(name="amount", type="smallint")
     */
    private $amount;

    /**
     * @ORM\ManyToOne(targetEntity="\Gesseh\RegisterBundle\Entity\Fee")
     * @ORM\JoinColumn(name="fee_id", referencedColumnName="id")
     */
    private $fee;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Gateway", cascade={"persist"})
     * @ORM\JoinColumn(name="method_id", referencedColumnName="id")
     */
    private $method;

    /**
     * @var Structure $structure
     *
     * @ORM\ManyToOne(targetEntity="\Gesseh\RegisterBundle\Entity\Structure", cascade={"persist"})
     * @ORM\JoinColumn(name="structure_id", referencedColumnName="id")
     */
    private $structure;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="payed_on", type="datetime", nullable=true)
     */
    private $payedOn;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expired_on", type="datetime")
     */
    private $expiredOn;

    /**
     * @ORM\OneToOne(targetEntity="\Gesseh\RegisterBundle\Entity\Payment")
     * @ORM\JoinColumn(name="payment_id")
     */
    private $payment;

    /**
     * @var string $status
     *
     * @ORM\Column(name="status", type="string", length=10)
     */
    private $status;

    /**
     * @var string $ref
     *
     * @ORM\Column(name="ref", type="string", length=50, nullable=true)
     */
    private $ref;

    /**
     * @var boolean $privacy
     *
     * @ORM\Column(name="privacy", type="boolean")
     */
    private $privacy;

    /**
     * @ORM\OneToMany(targetEntity="MemberInfo", mappedBy="membership", cascade={"remove", "persist"}, orphanRemoval=true)
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
     * Set person
     *
     * @param App\Entity\Person $person
     * @return Membership
     */
    public function setPerson(\Gesseh\UserBundle\Person $person = null)
    {
        $this->person = $person;

        return $this;
    }

    /**
     * Get person
     *
     * @return App\Entity\Person
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * Set amount
     *
     * @param integer $amount
     * @return Membership
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @param boolean $humanReadable
     * @return integer
     */
    public function getAmount($humanReadable = false)
    {
        if ($humanReadable)
            return number_format($this->amount / 100, 2,',',' ') . ' €';
        else
            return $this->amount;
    }

    /**
     * Set method
     *
     * @param Gateway $method
     * @return Membership
     */
    public function setMethod(Gateway $method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * Get method
     *
     * @return Gateway
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Set payedOn
     *
     * @param \DateTime $payedOn
     * @return Membership
     */
    public function setPayedOn($payedOn)
    {
        $this->payedOn = $payedOn;

        return $this;
    }

    /**
     * Get payedOn
     *
     * @return \DateTime
     */
    public function getPayedOn()
    {
        return $this->payedOn;
    }

    /**
     * Set expiredOn
     *
     * @param \DateTime $expiredOn
     * @return Membership
     */
    public function setExpiredOn($expiredOn)
    {
        $this->expiredOn = $expiredOn;

        return $this;
    }

    /**
     * Get expiredOn
     *
     * @return \DateTime
     */
    public function getExpiredOn()
    {
        return $this->expiredOn;
    }

    public function getPayment()
    {
        return $this->payment;
    }

    public function setPayment(Payment $payment)
    {
        $this->payment = $payment;
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
     * @return Membership
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

    /**
     * Set structure
     *
     * @param \Gesseh\RegisterBundle\Entity\Structure $structure
     *
     * @return Membership
     */
    public function setStructure(\Gesseh\RegisterBundle\Entity\Structure $structure = null)
    {
        $this->structure = $structure;

        return $this;
    }

    /**
     * Get structure
     *
     * @return \Gesseh\RegisterBundle\Entity\Structure
     */
    public function getStructure()
    {
        return $this->structure;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Membership
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set fee
     *
     * @param \Gesseh\RegisterBundle\Entity\Fee $fee
     *
     * @return Membership
     */
    public function setFee(\Gesseh\RegisterBundle\Entity\Fee $fee = null)
    {
        $this->fee = $fee;

        return $this;
    }

    /**
     * Get fee
     *
     * @return \Gesseh\RegisterBundle\Entity\Fee
     */
    public function getFee()
    {
        return $this->fee;
    }

    /**
     * Set ref
     *
     * @param string $ref
     *
     * @return Membership
     */
    public function setRef($ref)
    {
        $this->ref = $ref;

        return $this;
    }

    /**
     * Get ref
     *
     * @return string
     */
    public function getRef()
    {
        return $this->ref;
    }

    /**
     * Set privacy
     *
     * @param boolean $privacy
     *
     * @return Membership
     */
    public function setPrivacy($privacy)
    {
        $this->privacy = $privacy;
    }

    /**
     * Get privacy
     *
     * @return boolean
     */
    public function getPrivacy()
    {
        return $this->privacy;
    }

    /**
     * Is Rejoinable
     *
     * @return boolean
     */
    public function isRejoinable($date)
    {
        return ($this->status != 'registered' or $this->expiredOn <= $date)?true:false;
    }
}
