<?php

namespace CTO\AppBundle\Entity;

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
     * @Assert\NotBlank(message="This field can’t be blank")
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
     * @Assert\NotBlank(message="This field can’t be blank")
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
     * @ORM\OneToMany(targetEntity="CTO\AppBundle\Entity\ClientCars", mappedBy="ctoClient")
     */
    protected $cars;

    /**
     * @ORM\OneToOne(targetEntity="CTO\AppBundle\Entity\City")
     */
    protected $city;

    /**
     * @ORM\ManyToOne(targetEntity="CTO\AppBundle\Entity\CtoUser", inversedBy="clients")
     */
    protected $cto;

    public function __construct()
    {
        $this->cars = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getCto()
    {
        return $this->cto;
    }

    /**
     * @param mixed $cto
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
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
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
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
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
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getLastVisitDate()
    {
        return $this->lastVisitDate;
    }

    /**
     * @param mixed $lastVisitDate
     */
    public function setLastVisitDate($lastVisitDate)
    {
        $this->lastVisitDate = $lastVisitDate;
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
     * @param ClientCars $car
     * @return CtoClient
     */
    public function addCar(ClientCars $car)
    {
        $car->setCtoClient($this);
        $this->cars->add($car);

        return $this;
    }

    public function removeCar(ClientCars $car)
    {
        $this->cars->removeElement($car);
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }
}
