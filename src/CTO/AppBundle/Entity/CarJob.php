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
     * @ORM\Column(name="description", type="text")
     */
    protected $description;

    /**
     * @Assert\NotBlank(message="Обов'язкове поле")
     * @ORM\Column(name="price", type="float")
     */
    protected $price;

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
     * @ORM\ManyToOne(targetEntity="CTO\AppBundle\Entity\JobCategory", inversedBy="carJobs")
     */
    protected $jobCategory;

    /**
     * @ORM\OneToMany(targetEntity="CTO\AppBundle\Entity\Notification", mappedBy="carJob")
     */
    protected $notifications;

    public function __construct()
    {
        $this->notifications = new ArrayCollection();
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
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     * @return CarJob
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     * @return CarJob
     */
    public function setPrice($price)
    {
        $this->price = $price;

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
     * @return JobCategory
     */
    public function getJobCategory()
    {
        return $this->jobCategory;
    }

    /**
     * @param JobCategory $jobCategory
     * @return CarJob
     */
    public function setJobCategory(JobCategory $jobCategory)
    {
        $this->jobCategory = $jobCategory;

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
