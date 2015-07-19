<?php

namespace CTO\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class UsedMaterialsJob
 *
 * @ORM\Table(name="usedMaterialsJob")
 * @ORM\Entity()
 */
class UsedMaterialsJob implements \JsonSerializable
{
    use CreateUpdateTrait;

    /**
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

    /**
     * @Assert\NotBlank(message="Обов'язкове поле")
     * @Assert\Type(
     *   type="numeric",
     *   message="only float allowed"
     * )
     * @ORM\Column(name="price", type="float", nullable=true)
     */
    protected $price;

    /**
     * @ORM\ManyToOne(targetEntity="CTO\AppBundle\Entity\CarJob", inversedBy="usedMaterialsJob")
     */
    protected $carJob;

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
            "description" => $this->getDescription(),
            "price" => $this->getPrice()
        ];
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
     * @return UsedMaterialsJob
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
     * @return UsedMaterialsJob
     */
    public function setPrice($price)
    {
        $this->price = str_replace(',', '.', $price);

        return $this;
    }

    /**
     * @return CarJob
     */
    public function getCarJob()
    {
        return $this->carJob;
    }

    /**
     * @param CarJob $carJob
     * @return UsedMaterialsJob
     */
    public function setCarJob(CarJob $carJob)
    {
        $this->carJob = $carJob;

        return $this;
    }
}
