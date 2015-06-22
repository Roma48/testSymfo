<?php

namespace CTO\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ClientCars
 *
 * @ORM\Table(name="clientCars")
 * @ORM\Entity(repositoryClass="CTO\AppBundle\Entity\Repository\ClientCarsRepository")
 */
class ClientCars 
{
    use CreateUpdateTrait;

    /**
     * @ORM\Column(name="CarNumber", type="string", length=255, nullable=true)
     */
    protected $carNumber;

    /**
     * @ORM\Column(name="CarColor", type="string", length=255, nullable=true)
     */
    protected $carColor;

    /**
     * @ORM\OneToOne(targetEntity="CTO\AppBundle\Entity\Car")
     */
    protected $carBrand;

    /**
     * @ORM\OneToOne(targetEntity="CTO\AppBundle\Entity\Model")
     */
    protected $model;

    /**
     * @ORM\ManyToOne(targetEntity="CTO\AppBundle\Entity\CtoClient", inversedBy="cars")
     */
    protected $ctoClient;

    /**
     * @ORM\OneToMany(targetEntity="CTO\AppBundle\Entity\CarJob", mappedBy="car")
     */
    protected $carJobs;

    public function __construct()
    {
        $this->carJobs = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getCarJobs()
    {
        return $this->carJobs;
    }

    public function addCarJob(CarJob $carJob)
    {
        $carJob->setCar($this);
        $this->carJobs->add($carJob);

        return $this;
    }

    public function removeCarJob(CarJob $carJob)
    {
        $this->carJobs->removeElement($carJob);
    }

    /**
     * @return mixed
     */
    public function getCarNumber()
    {
        return $this->carNumber;
    }

    /**
     * @param mixed $carNumber
     * @return ClientCars
     */
    public function setCarNumber($carNumber)
    {
        $this->carNumber = $carNumber;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCarColor()
    {
        return $this->carColor;
    }

    /**
     * @param mixed $carColor
     * @return ClientCars
     */
    public function setCarColor($carColor)
    {
        $this->carColor = $carColor;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCarBrand()
    {
        return $this->$carBrand;
    }

    /**
     * @param Car $car
     * @return ClientCars
     */
    public function setCarBrand(Car $car)
    {
        $this->$carBrand = $car;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param Model $model
     * @return ClientCars
     */
    public function setModel(Model $model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCtoClient()
    {
        return $this->ctoClient;
    }

    /**
     * @param CtoClient $ctoClient
     * @return ClientCars
     */
    public function setCtoClient(CtoClient $ctoClient)
    {
        $this->ctoClient = $ctoClient;

        return $this;
    }
}
