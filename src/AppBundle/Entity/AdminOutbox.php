<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AdminOutbox
 *
 * @ORM\Table("admin_outbox")
 * @ORM\Entity
 */
class AdminOutbox
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
     * @var integer
     *
     * @ORM\Column(name="recipient", type="integer")
     */
    private $recipientId;

    /**
     * @var string
     * Message type: To, CC, BCC
     *
     * @ORM\Column(name="message_type", type="string", nullable=true)
     */
    private $messageType;

    /**
     * @var integer
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
     * Function name triggering message
     *
     * @ORM\Column(name="function", type="string")
     * 
     */
    private $function;
    
    /**
     * @ORM\Column(name="date", type="date")
     */
    private $date;


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
     * Set date
     *
     * @param date $date
     * @return AdminOutbox
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return integer 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set oppId
     *
     * @param string $oppId
     * @return AdminOutbox
     */
    public function setOppId($oppId)
    {
        $this->oppId = $oppId;

        return $this;
    }

    /**
     * Get oppId
     *
     * @return string 
     */
    public function getOppId()
    {
        return $this->oppId;
    }

    /**
     * Set function
     *
     * @return AdminOutbox
     */
    public function setFunction($function)
    {
        $this->function = $function;

        return $this;
    }

    /**
     * Get function
     *
     */
    public function getFunction()
    {
        return $this->function;
    }

    /**
     * Set recipientId
     *
     * @param integer $recipientId
     *
     * @return AdminOutbox
     */
    public function setRecipientId($recipientId)
    {
        $this->recipientId = $recipientId;

        return $this;
    }

    /**
     * Get recipientId
     *
     * @return integer
     */
    public function getRecipientId()
    {
        return $this->recipientId;
    }

    /**
     * Set messageType
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
     * Get messageType
     *
     * @return string
     */
    public function getMessageType()
    {
        return $this->messageType;
    }

    /**
     * Set userType
     *
     * @param integer $userType
     *
     * @return AdminOutbox
     */
    public function setUserType($userType)
    {
        $this->userType = $userType;

        return $this;
    }

    /**
     * Get userType
     *
     * @return integer
     */
    public function getUserType()
    {
        return $this->userType;
    }

    /**
     * Set orgId
     *
     * @param integer $orgId
     *
     * @return AdminOutbox
     */
    public function setOrgId($orgId)
    {
        $this->orgId = $orgId;

        return $this;
    }

    /**
     * Get orgId
     *
     * @return integer
     */
    public function getOrgId()
    {
        return $this->orgId;
    }
}
