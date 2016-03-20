<?php

namespace CTO\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Workplace
 * @package CTO\AppBundle\Entity
 *
 * @ORM\Table(name="workplaces")
 * @ORM\Entity(repositoryClass="CTO\AppBundle\Entity\Repository\WorkplaceRepository")
 */
class Workplace implements \JsonSerializable
{
    use CreateUpdateTrait;

    /**
     * @var string
     * @Assert\NotBlank(message="Обов'язкове поле")
     * @ORM\Column(name="title", type="string", length=255)
     */
    protected $title;

    /**
     * @var
     *
     * @ORM\OneToMany(targetEntity="CTO\AppBundle\Entity\Event", mappedBy="workplace")
     */
    protected $events;

    /**
     * @var CtoUser
     *
     * @ORM\ManyToOne(targetEntity="CTO\AppBundle\Entity\CtoUser", inversedBy="workplaces", cascade={"persist"})
     */
    protected $cto;

    public function __construct()
    {
        $this->events = new ArrayCollection();
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
            "id" => $this->getId(),
            "title" => $this->getTitle()
        ];
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * @param Event $event
     * @return $this
     */
    public function addEvent(Event $event)
    {
        $this->events->add($event);

        return $this;
    }

    /**
     * @param Event $event
     * @return $this
     */
    public function removeEvent(Event $event)
    {
        $this->events->remove($event);

        return $this;
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
     */
    public function setCto($cto)
    {
        $this->cto = $cto;
    }
}