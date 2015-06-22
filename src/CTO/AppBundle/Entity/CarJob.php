<?php

namespace CTO\AppBundle\Entity;

use DateTime;
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
     * @var DateTime
     *
     * @ORM\Column(name="birthDay", type="datetime", nullable=true)
     */
    protected $birthDay;

    /**
     * @ORM\Column(name="description", type="text")
     */
    protected $description;

    /**
     * @ORM\Column(name="price", type="float")
     */
    protected $price;

    /**
     * @ORM\ManyToOne(targetEntity="CTO\AppBundle\Entity\ClientCars", inversedBy="carJobs")
     */
    protected $car;

    /**
     * @ORM\OneToOne(targetEntity="CTO\AppBundle\Entity\JobCategory")
     */
    protected $jobCategory;

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return CarJob
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
     * @return CarJob
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getBirthDay()
    {
        return $this->birthDay;
    }

    /**
     * @param DateTime $birthDay
     * @return CarJob
     */
    public function setBirthDay($birthDay)
    {
        $this->birthDay = $birthDay;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCar()
    {
        return $this->car;
    }

    /**
     * @param ClientCars $car
     * @return CarJob
     */
    public function setCar(ClientCars $car)
    {
        $this->car = $car;

        return $this;
    }

    /**
     * @return mixed
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
}
