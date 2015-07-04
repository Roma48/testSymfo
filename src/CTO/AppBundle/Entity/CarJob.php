<?php

namespace CTO\AppBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class CarJob
 *
 * @ORM\Table(name="carJobs")
 * @ORM\Entity(repositoryClass="CTO\AppBundle\Entity\Repository\CarJobRepository")
 */
class CarJob 
{
    use CreateUpdateTrait;

    /**
     * @var DateTime
     *
     * @Assert\NotBlank(message="Обов'язкове поле")
     * @ORM\Column(name="jobDate", type="datetime", nullable=true)
     */
    protected $jobDate;

    /**
     * @Assert\NotBlank(message="Обов'язкове поле")
     * @ORM\ManyToOne(targetEntity="CTO\AppBundle\Entity\CtoClient", inversedBy="carJobs")
     */
    protected $client;

    /**
     * @ORM\ManyToOne(targetEntity="CTO\AppBundle\Entity\ClientCar", inversedBy="carJobs")
     */
    protected $car;

    /**
     * @ORM\OneToMany(targetEntity="CTO\AppBundle\Entity\CarCategory", mappedBy="carJob", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    protected $carCategories;

    /**
     * @ORM\OneToMany(targetEntity="CTO\AppBundle\Entity\Notification", mappedBy="carJob")
     */
    protected $notifications;

    public function __construct()
    {
        $this->notifications = new ArrayCollection();
        $this->carCategories = new ArrayCollection();
    }

    /**
     * @return DateTime
     */
    public function getJobDate()
    {
        return $this->jobDate;
    }

    /**
     * @param DateTime $jobDate
     * @return CarJob
     */
    public function setJobDate($jobDate)
    {
        $this->jobDate = $jobDate;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getNotifications()
    {
        return $this->notifications;
    }

    /**
     * @param Notification $notification
     * @return CarJob
     */
    public function addNotification(Notification $notification)
    {
        $notification->setCarJob($this);
        $this->notifications->add($notification);

        return $this;
    }

    /**
     * @param Notification $notification
     */
    public function removeNotification(Notification $notification)
    {
        $this->notifications->removeElement($notification);
    }

    /**
     * @return Collection
     */
    public function getCarCategories()
    {
        return $this->carCategories;
    }

    /**
     * @param CarCategory $carCategories
     * @return CarJob
     */
    public function addCarCategories(CarCategory $carCategories)
    {
        $carCategories->setCarJob($this);
        $this->carCategories->add($carCategories);

        return $this;
    }

    /**
     * @param CarCategory $carCategory
     */
    public function removeCarCategories(CarCategory $carCategory)
    {
        $this->carCategories->removeElement($carCategory);
    }

    /**
     * @return ClientCar
     */
    public function getCar()
    {
        return $this->car;
    }

    /**
     * @param ClientCar $car
     * @return CarJob
     */
    public function setCar(ClientCar $car)
    {
        $this->car = $car;

        return $this;
    }

    /**
     * @return CtoClient
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param CtoClient $client
     */
    public function setClient($client)
    {
        $this->client = $client;
    }
}
