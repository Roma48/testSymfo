<?php

namespace CTO\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class JobCategory
 *
 * @ORM\Table(name="jobCategory")
 * @ORM\Entity(repositoryClass="CTO\AppBundle\Entity\Repository\JobCategoryRepository")
 */
class JobCategory 
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
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return JobCategory
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}
