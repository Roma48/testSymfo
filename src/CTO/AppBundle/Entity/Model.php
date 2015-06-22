<?php

namespace CTO\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Model
 *
 * @ORM\Table(name="models")
 * @ORM\Entity()
 */
class Model 
{
    use CreateUpdateTrait;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="This field can’t be blank")
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
     * @return mixed
     */
    public function getCar()
    {
        return $this->car;
    }

    /**
     * @param mixed $car
     * @return Model
     */
    public function setCar($car)
    {
        $this->car = $car;

        return $this;
    }
}
