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
class JobCategory implements \JsonSerializable
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
     * @Assert\Type(
     *   type="numeric",
     *   message="допустимо лише цифри та крапка"
     * )
     * @ORM\Column(name="normHoursPrice", type="float", nullable=true)
     */
    protected $normHoursPrice;
    /**
     * @Assert\Type(
     *   type="numeric",
     *   message="допустимо лише цифри та крапка"
     * )
     * @ORM\Column(name="fixedPrice", type="float", nullable=true)
     */
    protected $fixedPrice;
    /**
     * @ORM\Column(name="isNormHours", type="boolean", nullable=true)
     */
    protected $normHours;

    /**
     * @ORM\OneToMany(targetEntity="CTO\AppBundle\Entity\CarCategory", mappedBy="jobCategory", cascade={"persist"})
     */
    protected $carJobs;
    /**
     * @ORM\ManyToOne(targetEntity="CTO\AppBundle\Entity\CtoUser", inversedBy="jobCategories")
     */
    protected $cto;

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
            "id" => (string) $this->getId(),
            "name" => $this->getName(),
            "isNormHours" => $this->isNormHours(),
            "normHoursPrice" => $this->getNormHoursPrice()
        ];
    }

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
//        $carJob->setJobCategory($this);
        $this->carJobs->add($carJob);

        return $this;
    }

    public function removeCarJob(CarJob $carJob)
    {
        $this->carJobs->removeElement($carJob);
    }

    /**
     * @return CtoUser
     */
    public function getCto()
    {
        return $this->cto;
    }

    /**
     * @param CtoUser $cto
     * @return JobCategory
     */
    public function setCto(CtoUser $cto)
    {
        $this->cto = $cto;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNormHoursPrice()
    {
        return $this->normHoursPrice;
    }

    /**
     * @param mixed $normHoursPrice
     * @return JobCategory
     */
    public function setNormHoursPrice($normHoursPrice)
    {
        $this->normHoursPrice = $normHoursPrice;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFixedPrice()
    {
        return $this->fixedPrice;
    }

    /**
     * @param mixed $fixedPrice
     * @return JobCategory
     */
    public function setFixedPrice($fixedPrice)
    {
        $this->fixedPrice = $fixedPrice;

        return $this;
    }

    /**
     * @return bool
     */
    public function isNormHours()
    {
        return $this->normHours;
    }

    /**
     * @param bool $normHours
     * @return JobCategory
     */
    public function setNormHours($normHours)
    {
        $this->normHours = $normHours;

        return $this;
    }
}
