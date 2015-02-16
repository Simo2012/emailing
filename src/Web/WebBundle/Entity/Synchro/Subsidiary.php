<?php

namespace Web\WebBundle\Entity\Synchro;

use Doctrine\ORM\Mapping as ORM;

/**
 * Subsidiary
 *
 * @ORM\Table(name="SYNCHRO.subsidiary", uniqueConstraints={@ORM\UniqueConstraint(name="subsidiaryShortname_uk", columns={"shortname"})}, indexes={@ORM\Index(name="subsidiaryName_idx", columns={"name"})})
 * @ORM\Entity
 */
class Subsidiary
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="shortname", type="string", length=10, nullable=false)
     */
    private $shortname;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=2, nullable=false)
     */
    private $country;



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Subsidiary
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set shortname
     *
     * @param string $shortname
     * @return Subsidiary
     */
    public function setShortname($shortname)
    {
        $this->shortname = $shortname;

        return $this;
    }

    /**
     * Get shortname
     *
     * @return string
     */
    public function getShortname()
    {
        return $this->shortname;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return Subsidiary
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }
}
