<?php

namespace CTO\AppBundle\Entity\DTO;

use DateTime;

class Broadcast
{
    protected $to;
    protected $description;
    protected $when;
    protected $autoSending;
    protected $adminCopy;
    protected $sendNow;

    /**
     * @return mixed
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param mixed $to
     * @return Broadcast
     */
    public function setTo($to)
    {
        $this->to = $to;

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
     * @return Broadcast
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getWhen()
    {
        return $this->when;
    }

    /**
     * @param DateTime $when
     * @return Broadcast
     */
    public function setWhen($when)
    {
        $this->when = $when;

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
     * @param mixed $autoSending
     * @return Broadcast
     */
    public function setAutoSending($autoSending)
    {
        $this->autoSending = $autoSending;

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
     * @param mixed $adminCopy
     * @return Broadcast
     */
    public function setAdminCopy($adminCopy)
    {
        $this->adminCopy = $adminCopy;

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
     * @param mixed $sendNow
     * @return Broadcast
     */
    public function setSendNow($sendNow)
    {
        $this->sendNow = $sendNow;

        return $this;
    }
}
