<?php

namespace CTO\AppBundle\Notification;

use CTO\AppBundle\Entity\CtoUser;
use CTO\AppBundle\Entity\Notification;
use Doctrine\ORM\EntityManager;
use Mcfedr\ResqueBundle\Manager\ResqueManager;
use Mcfedr\ResqueBundle\Worker\WorkerInterface;

class SMSSender implements WorkerInterface
{
    /** @var EntityManager $em */
    protected $em;
    protected $queueName;

    /** @var  ResqueManager */
    protected $resqueManager;

    protected $alfa_sms_name;
    protected $alfa_sms_ID;
    protected $alfa_sms_password;
    protected $alfa_sms_api_key;

    public function __construct(
        EntityManager $entityManager,
        ResqueManager $resqueManager,
        $queue_name,
        $alfa_sms_name,
        $alfa_sms_ID,
        $alfa_sms_password,
        $alfa_sms_api_key
    ) {
        $this->em = $entityManager;
        $this->resqueManager = $resqueManager;
        $this->queueName = $queue_name;

        $this->alfa_sms_name = $alfa_sms_name;
        $this->alfa_sms_ID = $alfa_sms_ID;
        $this->alfa_sms_password = $alfa_sms_password;
        $this->alfa_sms_api_key = $alfa_sms_api_key;
    }

    public function sendNow(Notification $notification, $sendCopyToAdmin = false, CtoUser $user)
    {
        $clientSMS = new SMSClient($this->alfa_sms_ID, $this->alfa_sms_password, $this->alfa_sms_api_key);

        try {
            $clientSMS->sendSMS($this->alfa_sms_name, '+38'.$user->getPhone(), $notification->getDescription());
        } catch (\Exception $e) {}

    }

    /**
     * Called to start the queued task
     *
     * @param array $options
     * @throws \Exception
     */
    public function execute(array $options = null)
    {
        // TODO: Implement execute() method.
    }


}
