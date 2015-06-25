<?php

namespace CTO\AppBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class CtoClient
 *
 * @ORM\Table(name="clients")
 * @ORM\Entity(repositoryClass="CTO\AppBundle\Entity\Repository\CtoClientRepository")
 */
class CtoClient 
{
    use CreateUpdateTrait;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="Обов'язкове поле")
     * @ORM\Column(name="firstName", type="string", length=255)
     */
    protected $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=255, nullable=true)
     */
    protected $lastName;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="Обов'язкове поле")
     * @ORM\Column(name="phone", type="string", length=255)
     */
    protected $phone;

    /**
     * @ORM\Column(name="lastVisitDate", type="datetime")
     */
    protected $lastVisitDate;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"firstName", "lastName"})
     * @ORM\Column(name="slug", type="string", length=255)
     */
    protected $slug;

    /**
     * @ORM\OneToMany(targetEntity="CTO\AppBundle\Entity\ClientCar", mappedBy="ctoClient", cascade={"persist"}, fetch="EAGER")
     */
    protected $cars;

    /**
     * @ORM\ManyToOne(targetEntity="CTO\AppBundle\Entity\City", inversedBy="ctoClients")
     */
    protected $city;

    /**
     * @ORM\ManyToOne(targetEntity="CTO\AppBundle\Entity\CtoUser", inversedBy="clients")
     */
    protected $cto;

    /**
     * @ORM\OneToMany(targetEntity="CTO\AppBundle\Entity\Notification", mappedBy="clientCto", cascade={"persist"})
     */
    protected $notifications;

    public function __construct()
    {
        $this->cars = new ArrayCollection();
        $this->notifications = new ArrayCollection();
    }

    /**
     * @return CtoUser
     */
    public function getCto()
    {
        return $this->cto;
    }

    /**
     * @param CtoUser $cto
     * @return CtoClient
     */
    public function setCto(CtoUser $cto)
    {
        $this->cto = $cto;

        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return CtoClient
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return CtoClient
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     * @return CtoClient
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getLastVisitDate()
    {
        return $this->lastVisitDate;
    }

    /**
     * @param DateTime $lastVisitDate
     * @return CtoClient
     */
    public function setLastVisitDate(DateTime $lastVisitDate)
    {
        $this->lastVisitDate = $lastVisitDate;

        return $this;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return Collection
     */
    public function getCars()
    {
        return $this->cars;
    }

    /**
     * @param ClientCar $car
     * @return CtoClient
     */
    public function addCar(ClientCar $car)
    {
        $car->setCtoClient($this);
        $this->cars->add($car);

        return $this;
    }

    public function removeCar(ClientCar $car)
    {
        $this->cars->removeElement($car);
    }

    /**
     * @return City
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param City $city
     * @return CtoClient
     */
    public function setCity(City $city)
    {
        $this->city = $city;

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
     * @return CtoClient
     */
    public function addNotification(Notification $notification)
    {
        $notification->setClientCto($this);
        $this->notifications->add($notification);

        return $this;
    }
}
