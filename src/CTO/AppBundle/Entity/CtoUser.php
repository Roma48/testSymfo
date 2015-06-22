<?php

namespace CTO\AppBundle\Entity;

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
     * @var string
     *
     * @Assert\NotBlank(message="This field can’t be blank")
     * @ORM\Column(name="ctoName", type="string", length=255)
     */
    protected $ctoName;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="This field can’t be blank")
     * @ORM\Column(name="city", type="string", length=255)
     */
    protected $city;

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

    public function __construct()
    {
        parent::__construct();
        $this->setRoles(self::ROLE_CTO_USER);
        $this->setBlocked(false);
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
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return CtoUser
     */
    public function setCity($city)
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
}
