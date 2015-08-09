<?php

namespace CTO\AppBundle\Notification;

use CTO\AppBundle\Entity\CtoClient;
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

    /** @var SMSClient $clientSMS */
    protected $clientSMS;

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

        $this->clientSMS = new SMSClient($alfa_sms_ID, $alfa_sms_password, $alfa_sms_api_key);
        $this->alfa_sms_name = $alfa_sms_name;

//        $this->alfa_sms_ID = $alfa_sms_ID;
//        $this->alfa_sms_password = $alfa_sms_password;
//        $this->alfa_sms_api_key = $alfa_sms_api_key;


    }

    public function getResqueManager()
    {
        return $this->resqueManager;
    }

    public function getBalance()
    {
        return $this->clientSMS->getBalance();
    }

    public function sendNow(Notification $notification, CtoClient $ctoClient, CtoUser $admin)
    {
//        $clientSMS = new SMSClient($this->alfa_sms_ID, $this->alfa_sms_password, $this->alfa_sms_api_key);

        try {
            $this->clientSMS->sendSMS($this->alfa_sms_name, '+38'.$ctoClient->getPhone(), $notification->getDescription());
            if ($notification->isAdminCopy()) {
                try {
                    $this->clientSMS->sendSMS($this->alfa_sms_name, '+38'.$admin->getPhone(), $notification->getDescription());
                } catch (\Exception $e) {}
            }
            $notification->setStatus(Notification::STATUS_SEND_OK);
        } catch (\Exception $e) {
            $notification->setStatus(Notification::STATUS_SEND_FAIL);
        }
    }

    /**
     * Called to start the queued task
     *
     * @param array $options
     * @throws \Exception
     */
    public function execute(array $options = null)
    {
        $notificationId = $options['notificationId'];
        $clientId = $options['clientId'];
        $adminId = $options['adminId'];

        $notification = $this->em->getRepository('CTOAppBundle:Notification')->find($notificationId);
        $ctoClient = $this->em->getRepository('CTOAppBundle:CtoClient')->find($clientId);

//        $clientSMS = new SMSClient($this->alfa_sms_ID, $this->alfa_sms_password, $this->alfa_sms_api_key);

        try {
            $this->clientSMS->sendSMS($this->alfa_sms_name, '+38'.$ctoClient->getPhone(), $notification->getDescription());
            if ($notification->isAdminCopy()) {
                $admin = $this->em->getRepository('CTOAppBundle:CtoUser')->find($adminId);
                try {
                    $this->clientSMS->sendSMS($this->alfa_sms_name, '+38'.$admin->getPhone(), $notification->getDescription());
                } catch(\Exception $e) {
                    $notification->setStatus(Notification::STATUS_SEND_FAIL);
                    $this->em->flush();

                    return;
                }
            }
            $notification->setStatus(Notification::STATUS_SEND_OK);
            $this->em->flush();
        } catch (\Exception $e) {
            $notification->setStatus(Notification::STATUS_SEND_FAIL);
            $this->em->flush();
        }
    }
}
