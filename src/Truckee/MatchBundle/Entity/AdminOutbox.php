<?php

namespace Truckee\MatchBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AdminOutbox.
 *
 * @ORM\Table("admin_outbox")
 * @ORM\Entity
 */
class AdminOutbox
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="recipient", type="integer")
     */
    private $recipientId;

    /**
     * @var string
     *             Message type: To, CC, BCC
     *
     * @ORM\Column(name="message_type", type="string", nullable=true)
     */
    private $messageType;

    /**
     * @var int
     *
     * @ORM\Column(name="user_type", type="string", nullable=true)
     */
    private $userType;

    /**
     * @var string
     *
     * @ORM\Column(name="oppId", type="integer", nullable=true)
     */
    private $oppId;

    /**
     * @var string
     *
     * @ORM\Column(name="orgId", type="integer", nullable=true)
     */
    private $orgId;

    /**
     * @var string
     *             Function name triggering message
     *
     * @ORM\Column(name="function", type="string")
     */
    private $function;

    /**
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date.
     *
     * @param date $date
     *
     * @return AdminOutbox
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date.
     *
     * @return int
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set oppId.
     *
     * @param string $oppId
     *
     * @return AdminOutbox
     */
    public function setOppId($oppId)
    {
        $this->oppId = $oppId;

        return $this;
    }

    /**
     * Get oppId.
     *
     * @return string
     */
    public function getOppId()
    {
        return $this->oppId;
    }

    /**
     * Set function.
     *
     * @return AdminOutbox
     */
    public function setFunction($function)
    {
        $this->function = $function;

        return $this;
    }

    /**
     * Get function.
     */
    public function getFunction()
    {
        return $this->function;
    }

    /**
     * Set recipientId.
     *
     * @param int $recipientId
     *
     * @return AdminOutbox
     */
    public function setRecipientId($recipientId)
    {
        $this->recipientId = $recipientId;

        return $this;
    }

    /**
     * Get recipientId.
     *
     * @return int
     */
    public function getRecipientId()
    {
        return $this->recipientId;
    }

    /**
     * Set messageType.
     *
     * @param string $messageType
     *
     * @return AdminOutbox
     */
    public function setMessageType($messageType)
    {
        $this->messageType = $messageType;

        return $this;
    }

    /**
     * Get messageType.
     *
     * @return string
     */
    public function getMessageType()
    {
        return $this->messageType;
    }

    /**
     * Set userType.
     *
     * @param int $userType
     *
     * @return AdminOutbox
     */
    public function setUserType($userType)
    {
        $this->userType = $userType;

        return $this;
    }

    /**
     * Get userType.
     *
     * @return int
     */
    public function getUserType()
    {
        return $this->userType;
    }

    /**
     * Set orgId.
     *
     * @param int $orgId
     *
     * @return AdminOutbox
     */
    public function setOrgId($orgId)
    {
        $this->orgId = $orgId;

        return $this;
    }

    /**
     * Get orgId.
     *
     * @return int
     */
    public function getOrgId()
    {
        return $this->orgId;
    }
}
