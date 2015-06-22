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

    const TYPE_AUTO = 'auto';
    const TYPE_MANUAL = 'manual';
    const TYPE_BROADCAST = 'broadcast';
    const STATUS_SEND_OK = 'ok';
    const STATUS_SEND_IN_PROGRESS = 'in-progress';
    const STATUS_SEND_FAIL = 'fail';

    /**
     * @ORM\Column(name="status", type="string", length=20)
     */
    protected $status;

    /**
     * @ORM\Column(name="type", type="string", length=20)
     */
    protected $type;

    /**
     * @ORM\Column(name="description", type="text")
     */
    protected $description;

    /**
     * @ORM\Column(name="whenSend", type="datetime")
     */
    protected $whenSend;

    /**
     * @ORM\ManyToOne(targetEntity="CTO\AppBundle\Entity\CtoClient", inversedBy="notifications")
     */
    protected $clientCto;

    /**
     * @ORM\ManyToOne(targetEntity="CTO\AppBundle\Entity\CarJob", inversedBy="notifications")
     */
    protected $carJob;

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
     */
    public function setWhenSend(DateTime $whenSend)
    {
        $this->whenSend = $whenSend;
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
}
