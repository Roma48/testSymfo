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
class ClientCar implements \JsonSerializable
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
     * @Assert\Type(
     *   type="numeric",
     *   message="тільки цифри допустимі"
     * )
     * @ORM\Column(name="engine", type="float", nullable=true)
     */
    protected $engine;

    /**
     * @ORM\Column(name="vinCode", type="string", nullable=true)
     */
    protected $vinCode;

    /**
     * @ORM\Column(name="createYear", type="string", length=20, nullable=true)
     */
    protected $createYear;

    protected $carModel;

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

    /**
     * (PHP 5 &gt;= 5.4.0)<br/>
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     */
    function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getModel()->getName()
        ];
    }


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
        $this->carBrand = $car;

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

    /**
     * @return mixed
     */
    public function getCarModel()
    {
        return $this->model->getName();
    }

    /**
     * @return mixed
     */
    public function getEngine()
    {
        return $this->engine;
    }

    /**
     * @param mixed $engine
     * @return ClientCar
     */
    public function setEngine($engine)
    {
        $this->engine = (float)str_replace(',', '.', $engine);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getVinCode()
    {
        return $this->vinCode;
    }

    /**
     * @param mixed $vinCode
     * @return ClientCar
     */
    public function setVinCode($vinCode)
    {
        $this->vinCode = $vinCode;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreateYear()
    {
        return $this->createYear;
    }

    /**
     * @param mixed $createYear
     * @return ClientCar
     */
    public function setCreateYear($createYear)
    {
        $this->createYear = $createYear;

        return $this;
    }
}
