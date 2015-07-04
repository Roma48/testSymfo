<?php

namespace CTO\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @Assert\NotBlank(message="Обов'язкове поле")
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="CTO\AppBundle\Entity\CarCategory", mappedBy="jobCategory", cascade={"persist"})
     */
    protected $carJobs;

    public function __construct()
    {
        $this->carJobs = new ArrayCollection();
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
     * @return JobCategory
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCarJobs()
    {
        return $this->carJobs;
    }

    /**
     * @param Collection $carJobs
     */
    public function setCarJobs($carJobs)
    {
        $this->carJobs = $carJobs;
    }

    /**
     * @param CarJob $carJob
     * @return JobCategory
     */
    public function addCarJob(CarJob $carJob)
    {
        $carJob->setJobCategory($this);
        $this->carJobs->add($carJob);

        return $this;
    }

    public function removeCarJob(CarJob $carJob)
    {
        $this->carJobs->removeElement($carJob);
    }
}
