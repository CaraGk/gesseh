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
     * @ORM\ManyToOne(targetEntity="\Gesseh\UserBundle\Entity\Student", cascade={"persist"})
     * @ORM\JoinColumn(name="student_id", referencedColumnName="id")
     */
    private $student;

    /**
     * @var integer
     *
     * @ORM\Column(name="amount", type="decimal", precision=2)
     */
    private $amount;

    /**
     * @ORM\ManyToOne(targetEntity="Gateway", cascade={"persist"})
     * @ORM\JoinColumn(name="method_id", referencedColumnName="id")
     */
    private $method;

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
     * Set student
     *
     * @param Gesseh\UserBundle\Entity\Student $student
     * @return Membership
     */
    public function setStudent(\Gesseh\UserBundle\Entity\Student $student = null)
    {
        $this->student = $student;

        return $this;
    }

    /**
     * Get student
     *
     * @return Gesseh\UserBundle\Entity\Student
     */
    public function getStudent()
    {
        return $this->student;
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
     * @return integer
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set method
     *
     * @param string $method
     * @return Membership
     */
    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * Get method
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Get readable method
     *
     * @return string
     */
    public function getReadableMethod()
    {
        if ($this->method == 1 or $this->method == 'offline')
            return 'Chèque';
        elseif ($this->method == 2 or $this->method == 'paypal')
            return 'Paypal';
        elseif ($this->method == 3 or $this->method == 'sips')
            return 'Carte bancaire';
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
}
