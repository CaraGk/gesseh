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
use JMS\Payment\CoreBundle\Entity\PaymentInstruction;

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
     * @ORM\Column(name="payment", type="decimal", precision=2)
     */
    private $payment;

    /**
     * @var string
     *
     * @ORM\Column(name="method", type="string", length=255)
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
     * @ORM\OneToOne(targetEntity="\JMS\Payment\CoreBundle\Entity\PaymentInstruction")
     * @ORM\JoinColumn(name="payment_instruction_id", referencedColumnName="id")
     */
    private $paymentInstruction;


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
     * Set payment
     *
     * @param integer $payment
     * @return Membership
     */
    public function setPayment($payment)
    {
        $this->payment = $payment;

        return $this;
    }

    /**
     * Get payment
     *
     * @return integer
     */
    public function getPayment()
    {
        return $this->payment;
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

    public function getPaymentInstruction()
    {
        return $this->paymentInstruction;
    }

    public function setPaymentInstruction(PaymentInstruction $instruction)
    {
        $this->paymentInstruction = $instruction;
    }
}

