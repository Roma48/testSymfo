<?php

namespace CTO\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class Model
 *
 * @ORM\Table(name="models")
 * @ORM\Entity()
 */
class Model implements \JsonSerializable
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
     * @ORM\ManyToOne(targetEntity="CTO\AppBundle\Entity\Car", inversedBy="models")
     */
    protected $car;

    /**
     * @ORM\OneToMany(targetEntity="CTO\AppBundle\Entity\ClientCar", mappedBy="model", cascade={"persist"})
     */
    protected $clientCars;

    public function __construct()
    {
        $this->clientCars = new ArrayCollection();
    }

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
            "slugCar" => $this->getCar()->getSlug(),
            "slugModel" => $this->getSlug(),
            "name" => $this->getName(),
            "id" => $this->getId()
        ];
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
     * @return Model
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
     * @return Car
     */
    public function getCar()
    {
        return $this->car;
    }

    /**
     * @param Car $car
     * @return Model
     */
    public function setCar(Car $car)
    {
        $this->car = $car;

        return $this;
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
     * @return Model
     */
    public function addClientCar(ClientCar $clientCar)
    {
        $clientCar->setModel($this);
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
