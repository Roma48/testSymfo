<?php

namespace CTO\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class CategoryJobDescription
 *
 * @ORM\Table(name="jobDescription")
 * @ORM\Entity()
 */
class CategoryJobDescription 
{
    use CreateUpdateTrait;

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
     * @ORM\ManyToOne(targetEntity="CTO\AppBundle\Entity\CarCategory", inversedBy="jobDescriptions")
     */
    protected $carCategory;

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
     * @return CategoryJobDescription
     */
    public function getCarCategory()
    {
        return $this->carCategory;
    }

    /**
     * @param CarCategory $carCategory
     * @return CategoryJobDescription
     */
    public function setCarCategory(CarCategory $carCategory)
    {
        $this->carCategory = $carCategory;

        return $this;
    }
}
