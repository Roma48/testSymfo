<?php

namespace CTO\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class CarCategory
 *
 * @ORM\Table(name="carCategory")
 * @ORM\Entity()
 */
class CarCategory 
{
    use CreateUpdateTrait;

    /**
     * @ORM\ManyToOne(targetEntity="CTO\AppBundle\Entity\JobCategory", inversedBy="carJobs")
     */
    protected $jobCategory;

    /**
     * @ORM\ManyToOne(targetEntity="CTO\AppBundle\Entity\CarJob", inversedBy="carCategories")
     */
    protected $carJob;

    /**
     * @ORM\OneToMany(targetEntity="CTO\AppBundle\Entity\CategoryJobDescription", mappedBy="carCategory", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    protected $jobDescriptions;

    public function __construct()
    {
        $this->jobDescriptions = new ArrayCollection();
    }

    /**
     * @return JobCategory
     */
    public function getJobCategory()
    {
        return $this->jobCategory;
    }

    /**
     * @param JobCategory $jobCategory
     * @return CarCategory
     */
    public function setJobCategory(JobCategory $jobCategory)
    {
        $this->jobCategory = $jobCategory;

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
     * @return CarCategory
     */
    public function setCarJob(CarJob $carJob)
    {
        $this->carJob = $carJob;
    }

    /**
     * @return Collection
     */
    public function getJobDescriptions()
    {
        return $this->jobDescriptions;
    }

    /**
     * @param CategoryJobDescription $categoryJobDescription
     * @return CarCategory
     */
    public function addJobDescription(CategoryJobDescription $categoryJobDescription)
    {
        $categoryJobDescription->setCarCategory($this);
        $this->jobDescriptions->add($categoryJobDescription);

        return $this;
    }

    /**
     * @param CategoryJobDescription $categoryJobDescription
     */
    public function removeJobDescription(CategoryJobDescription $categoryJobDescription)
    {
        $this->jobDescriptions->removeElement($categoryJobDescription);
    }
}
