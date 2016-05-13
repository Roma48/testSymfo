<?php

namespace CTO\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Event
 * @package CTO\AppBundle\Entity
 *
 * @ORM\Table(name="events")
 * @ORM\Entity(repositoryClass="CTO\AppBundle\Entity\Repository\EventRepository")
 */
class Event implements \JsonSerializable
{
    use CreateUpdateTrait;

    /**
     * @ORM\ManyToOne(targetEntity="CTO\AppBundle\Entity\CtoClient", inversedBy="events")
     */
    protected $client;

    /**
     * @ORM\ManyToOne(targetEntity="CTO\AppBundle\Entity\ClientCar", inversedBy="events")
     */
    protected $car;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="Обов'язкове поле")
     * @ORM\Column(name="description", type="text")
     */
    protected $description;

    /**
     * @var \DateTime
     *
     * @Assert\NotBlank(message="Обов'язкове поле")
     * @ORM\Column(name="start_at", type="datetime")
     */
    protected $startAt;

    /**
     * @var \DateTime
     *
     * @Assert\NotBlank(message="Обов'язкове поле")
     * @ORM\Column(name="end_at", type="datetime")
     */
    protected $endAt;

    /**
     * @var CtoUser
     *
     * @ORM\ManyToOne(targetEntity="CTO\AppBundle\Entity\CtoUser", inversedBy="events")
     */
    protected $cto;

    /**
     * @var
     *
     * @ORM\ManyToOne(targetEntity="CTO\AppBundle\Entity\Workplace", inversedBy="events", cascade={"persist"})
     */
    protected $workplace;

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
            "id" => $this->getId(),
            "client" => $this->getClient(),
            "car" => $this->getCar(),
            "description" => $this->getDescription(),
            "start" => $this->getStartAt(),
            "end" => $this->getEndAt(),
            "workplace" => $this->getWorkplace()
        ];
    }

    /**
     * @return mixed
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param CtoClient $client
     * @return $this
     */
    public function setClient(CtoClient $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCar()
    {
        return $this->car;
    }

    /**
     * @param ClientCar $car
     * @return $this
     */
    public function setCar(ClientCar $car)
    {
        $this->car = $car;

        return $this;
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
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return \DateTime
     */
    public function getStartAt()
    {
        return $this->startAt;
    }

    /**
     * @param \DateTime $startAt
     */
    public function setStartAt($startAt)
    {
        $this->startAt = $startAt;
    }

    /**
     * @return \DateTime
     */
    public function getEndAt()
    {
        return $this->endAt;
    }

    /**
     * @param \DateTime $endAt
     */
    public function setEndAt($endAt)
    {
        $this->endAt = $endAt;
    }

    /**
     * @return BaseUser
     */
    public function getCto()
    {
        return $this->cto;
    }

    /**
     * @param CtoUser $cto
     * @return $this
     */
    public function setCto(CtoUser $cto)
    {
        $this->cto = $cto;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getWorkplace()
    {
        return $this->workplace;
    }

    /**
     * @param mixed $workplace
     */
    public function setWorkplace($workplace)
    {
        $this->workplace = $workplace;
    }
}