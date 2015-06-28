<?php

namespace CTO\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ClientCar
 *
 * @ORM\Table(name="clientCars")
 * @ORM\Entity(repositoryClass="CTO\AppBundle\Entity\Repository\ClientCarsRepository")
 */
class ClientCar
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
     * @ORM\ManyToOne(targetEntity="CTO\AppBundle\Entity\Car", inversedBy="clientCars", fetch="EAGER")
     */
    protected $carBrand;

    /**
     * @ORM\ManyToOne(targetEntity="CTO\AppBundle\Entity\Model", inversedBy="clientCars", fetch="EAGER")
     */
    protected $model;

    /**
     * @ORM\ManyToOne(targetEntity="CTO\AppBundle\Entity\CtoClient", inversedBy="cars")
     */
    protected $ctoClient;

    /**
     * @ORM\OneToMany(targetEntity="CTO\AppBundle\Entity\CarJob", mappedBy="car", cascade={"persist"})
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
     * @return ClientCar
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
     * @return ClientCar
     */
    public function setCarColor($carColor)
    {
        $this->carColor = $carColor;

        return $this;
    }

    /**
     * @return Car
     */
    public function getCarBrand()
    {
        return $this->model->getCar();// $carBrand;
    }

    /**
     * @param Car $car
     * @return ClientCar
     */
    public function setCarBrand(Car $car)
    {
        $this->$carBrand = $car;

        return $this;
    }

    /**
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param Model $model
     * @return ClientCar
     */
    public function setModel(Model $model)
    {
        $this->model = $model;
        $car = $model->getCar();
        $car->addClientCar($this);
        $this->carBrand = $car;
//        $this->setCarBrand($model->getCar());

        return $this;
    }

    /**
     * @return CtoClient
     */
    public function getCtoClient()
    {
        return $this->ctoClient;
    }

    /**
     * @param CtoClient $ctoClient
     * @return ClientCar
     */
    public function setCtoClient(CtoClient $ctoClient)
    {
        $this->ctoClient = $ctoClient;

        return $this;
    }
}
