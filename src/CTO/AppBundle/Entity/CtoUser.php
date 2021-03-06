<?php

namespace CTO\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class CtoUser
 *
 * @ORM\Entity(repositoryClass="CTO\AppBundle\Entity\Repository\CtoUserRepository")
 */
class CtoUser extends BaseUser
{

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"firstName", "lastName"})
     * @ORM\Column(name="slug", type="string", length=255)
     */
    protected $slug;

    /**
     * @Assert\Length(max=20, maxMessage="Не більше {{ limit }} символів")
     * @Assert\Regex(pattern="/^[+]?[0-9+() -]+$/", message="Допустимі тільки цифри")
     * @ORM\Column(name="phone2", type="string", nullable=true)
     */
    protected $phone2;
    /**
     * @Assert\Length(max=20, maxMessage="Не більше {{ limit }} символів")
     * @Assert\Regex(pattern="/^[+]?[0-9+() -]+$/", message="Допустимі тільки цифри")
     * @ORM\Column(name="phone3", type="string", nullable=true)
     */
    protected $phone3;
    /**
     * @Assert\NotBlank(message="Обов'язкове поле")
     * @ORM\Column(name="addressCto", type="string", nullable=true)
     */
    protected $addressCto;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="Обов'язкове поле")
     * @ORM\Column(name="ctoName", type="string", length=255)
     */
    protected $ctoName;

    /**
     * @var bool
     *
     * @ORM\Column(name="blocked", type="boolean")
     */
    protected $blocked;

    /**
     * @var bool
     *
     * @ORM\Column(name="publicProfile", type="boolean")
     */
    protected $publicProfile;

    /**
     * @ORM\OneToMany(targetEntity="CTO\AppBundle\Entity\CtoClient", mappedBy="cto")
     */
    protected $clients;

    /**
     * @ORM\ManyToOne(targetEntity="CTO\AppBundle\Entity\City", inversedBy="ctoUsers")
     */
    protected $city;

    /**
     * @ORM\OneToMany(targetEntity="CTO\AppBundle\Entity\JobCategory", mappedBy="cto", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    protected $jobCategories;

    public function __construct()
    {
        parent::__construct();
        $this->setRoles([self::ROLE_CTO_USER]);
        $this->setBlocked(false);
        $this->clients = new ArrayCollection();
        $this->jobCategories = new ArrayCollection();
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
     * @return CtoUser
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return string
     */
    public function getCtoName()
    {
        return $this->ctoName;
    }

    /**
     * @param string $ctoName
     * @return CtoUser
     */
    public function setCtoName($ctoName)
    {
        $this->ctoName = $ctoName;

        return $this;
    }

    /**
     * @return City
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param City $city
     * @return CtoUser
     */
    public function setCity(City $city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return bool
     */
    public function isBlocked()
    {
        return $this->blocked;
    }

    /**
     * @param bool $blocked
     * @return CtoUser
     */
    public function setBlocked($blocked)
    {
        $this->blocked = $blocked;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isPublicProfile()
    {
        return $this->publicProfile;
    }

    /**
     * @param boolean $publicProfile
     * @return CtoUser
     */
    public function setPublicProfile($publicProfile)
    {
        $this->publicProfile = $publicProfile;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getClients()
    {
        return $this->clients;
    }

    /**
     * @param CtoClient $client
     */
    public function addClient(CtoClient $client)
    {
        $client->setCto($this);
        $this->clients->add($client);
    }

    /**
     * @param CtoClient $client
     */
    public function removeClient(CtoClient $client)
    {
        $this->clients->removeElement($client);
    }

    /**
     * @return Collection
     */
    public function getJobCategories()
    {
        return $this->jobCategories;
    }

    /**
     * @param JobCategory $category
     * @return CtoUser
     */
    public function addJobCategory(JobCategory $category)
    {
        if (!$this->getJobCategories()->contains($category)) {
            $category->setCto($this);
            $this->jobCategories->add($category);
        }

        return $this;
    }

    /**
     * @param JobCategory $category
     */
    public function removeJobCategory(JobCategory $category)
    {
        $this->jobCategories->removeElement($category);
    }

    /**
     * @return mixed
     */
    public function getPhone2()
    {
        return $this->phone2;
    }

    /**
     * @param mixed $phone2
     * @return CtoUser
     */
    public function setPhone2($phone2)
    {
        $this->phone2 = $phone2;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhone3()
    {
        return $this->phone3;
    }

    /**
     * @param mixed $phone3
     * @return CtoUser
     */
    public function setPhone3($phone3)
    {
        $this->phone3 = $phone3;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAddressCto()
    {
        return $this->addressCto;
    }

    /**
     * @param mixed $addressCto
     * @return CtoUser
     */
    public function setAddressCto($addressCto)
    {
        $this->addressCto = $addressCto;

        return $this;
    }
}
