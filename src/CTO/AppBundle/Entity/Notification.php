<?php

namespace CTO\AppBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Notification
 *
 * @ORM\Table(name="notifications")
 * @ORM\Entity(repositoryClass="CTO\AppBundle\Entity\Repository\NotificationRepository")
 */
class Notification 
{
    use CreateUpdateTrait;

    const TYPE_NOTIFICATION = 'notification';
    const TYPE_RECOMENDATION = 'recomendation';
    const TYPE_BROADCAST = 'broadcast';
    const STATUS_SEND_OK = 'ok';
    const STATUS_SEND_IN_PROGRESS = 'in-progress';
    const STATUS_SEND_FAIL = 'fail';
    const STATUS_ABORTED = 'aborted';

    /**
     * @ORM\Column(name="status", type="string", length=20)
     */
    protected $status;

    /**
     * @ORM\Column(name="type", type="string", length=20)
     */
    protected $type;

    /**
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

    /**
     * @ORM\Column(name="whenSend", type="datetime")
     */
    protected $whenSend;

    /**
     * @ORM\Column(name="smsId", type="string", length=255, nullable=true)
     */
    protected $smsId;

    /**
     * @ORM\Column(name="autoSending", type="boolean", nullable=true)
     */
    protected $autoSending;

    /**
     * @ORM\Column(name="adminCopy", type="boolean", nullable=true)
     */
    protected $adminCopy;

    /**
     * @ORM\Column(name="sendNow", type="boolean", nullable=true)
     */
    protected $sendNow;

    /**
     * @ORM\Column(name="resqueJobDescription", type="object", nullable=true)
     */
    protected $resqueJobDescription;

    /**
     * @ORM\Column(name="broadcastTo", type="string", nullable=true)
     */
    protected $broadcastTo;

    /**
     * @ORM\ManyToOne(targetEntity="CTO\AppBundle\Entity\CtoClient", inversedBy="notifications")
     */
    protected $clientCto;

    /**
     * @ORM\ManyToOne(targetEntity="CTO\AppBundle\Entity\CarJob", inversedBy="notifications")
     */
    protected $carJob;

    /**
     * @ORM\ManyToOne(targetEntity="CTO\AppBundle\Entity\CarCategory")
     */
    protected $carJobCategory;

    /**
     * @ORM\ManyToOne(targetEntity="CTO\AppBundle\Entity\CtoUser")
     */
    protected $userCto;

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     *
     * @return Notification
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return DateTime
     */
    public function getWhenSend()
    {
        return $this->whenSend;
    }

    /**
     * @param DateTime $whenSend
     * @return Notification
     */
    public function setWhenSend($whenSend)
    {
        $this->whenSend = $whenSend;

        return $this;
    }

    /**
     * @return CtoUser
     */
    public function getUserCto()
    {
        return $this->userCto;
    }

    /**
     * @param CtoUser $userCto
     * @return Notification
     */
    public function setUserCto(CtoUser $userCto)
    {
        $this->userCto = $userCto;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return Notification
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBroadcastTo()
    {
        return $this->broadcastTo;
    }

    /**
     * @param mixed $broadcastTo
     * @return Notification
     */
    public function setBroadcastTo($broadcastTo)
    {
        $this->broadcastTo = $broadcastTo;

        return $this;
    }

    /**
     * @return CtoClient
     */
    public function getClientCto()
    {
        return $this->clientCto;
    }

    /**
     * @param CtoClient $clientCto
     * @return Notification
     */
    public function setClientCto(CtoClient $clientCto)
    {
        $this->clientCto = $clientCto;

        return $this;
    }

    /**
     * @return CarJob
     */
    public function getCarJob()
    {
        return $this->carJob;
    }

    /**
     * @param CarJob $carJob
     * @return Notification
     */
    public function setCarJob(CarJob $carJob)
    {
        $this->carJob = $carJob;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSmsId()
    {
        return $this->smsId;
    }

    /**
     * @param mixed $smsId
     * @return Notification
     */
    public function setSmsId($smsId)
    {
        $this->smsId = $smsId;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isAutoSending()
    {
        return $this->autoSending;
    }

    /**
     * @param boolean $autoSending
     * @return Notification
     */
    public function setAutoSending($autoSending)
    {
        $this->autoSending = $autoSending;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isSendNow()
    {
        return $this->sendNow;
    }

    /**
     * @param boolean $notificationDone
     * @return Notification
     */
    public function setSendNow($notificationDone)
    {
        $this->sendNow = $notificationDone;

        return $this;
    }

    /**
     * @return CarCategory
     */
    public function getCarJobCategory()
    {
        return $this->carJobCategory;
    }

    /**
     * @param CarCategory $carJobCategory
     * @return Notification
     */
    public function setCarJobCategory(CarCategory $carJobCategory)
    {
        $this->carJobCategory = $carJobCategory;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isAdminCopy()
    {
        return $this->adminCopy;
    }

    /**
     * @param boolean $adminCopy
     * @return Notification
     */
    public function setAdminCopy($adminCopy)
    {
        $this->adminCopy = $adminCopy;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getResqueJobDescription()
    {
        return $this->resqueJobDescription;
    }

    /**
     * @param mixed $resqueJobDescription
     * @return Notification
     */
    public function setResqueJobDescription($resqueJobDescription)
    {
        $this->resqueJobDescription = $resqueJobDescription;

        return $this;
    }
}
