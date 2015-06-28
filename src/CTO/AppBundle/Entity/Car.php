<?php

namespace CTO\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class Car
 *
 * @ORM\Table(name="cars")
 * @ORM\Entity()
 */
class Car 
{
    use CreateUpdateTrait;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="Обов'язкове поле")
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(name="slug", type="string", length=255)
     */
    protected $slug;

    /**
     * @ORM\OneToMany(targetEntity="CTO\AppBundle\Entity\Model", mappedBy="car", cascade={"persist"})
     */
    protected $models;

    /**
     * @ORM\OneToMany(targetEntity="CTO\AppBundle\Entity\ClientCar", mappedBy="carBrand", cascade={"persist"})
     */
    protected $clientCars;

    public function __construct()
    {
        $this->models = new ArrayCollection();
        $this->clientCars = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Car
     */
    public function setName($name)
    {
        $this->name = $name;

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
    public function getModels()
    {
        return $this->models;
    }

    /**
     * @param Model $model
     * @return Car
     */
    public function addModel(Model $model)
    {
        $model->setCar($this);
        $this->models->add($model);

        return $this;
    }

    public function removeModel(Model $model)
    {
        $this->models->removeElement($model);
    }

    /**
     * @return Collection
     */
    public function getClientCars()
    {
        return $this->clientCars;
    }

    /**
     * @param ClientCar $clientCar
     * @return Car
     */
    public function addClientCar(ClientCar $clientCar)
    {
//        $clientCar->setCarBrand($this);
        $this->clientCars->add($clientCar);

        return $this;
    }

    /**
     * @param ClientCar $clientCar
     */
    public function removeClientCar(ClientCar $clientCar)
    {
        $this->clientCars->removeElement($clientCar);
    }
}
