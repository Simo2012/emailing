<?php

namespace Web\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BrandRubizz
 *
 * @ORM\Table(name="brand_rubizz", indexes={@ORM\Index(name="brandRubizzBrand_fk", columns={"brand_id"}), @ORM\Index(name="brandRubizzSector_fk", columns={"sector_id"})})
 * @ORM\Entity
 */
class BrandRubizz
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
     * @var \Brand
     *
     * @ORM\ManyToOne(targetEntity="Web\WebBundle\Entity\Synchro\Brand")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="brand_id", referencedColumnName="id")
     * })
     */
    private $brand;

    /**
     * @var \Sector
     *
     * @ORM\ManyToOne(targetEntity="Sector")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="sector_id", referencedColumnName="id")
     * })
     */
    private $sector;



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
     * Set brand
     *
     * @param \Web\WebBundle\Entity\Synchro\Brand $brand
     * @return BrandRubizz
     */
    public function setBrand(\Web\WebBundle\Entity\Synchro\Brand $brand = null)
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * Get brand
     *
     * @return \Web\WebBundle\Entity\Synchro\Brand
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * Set sector
     *
     * @param \Web\WebBundle\Entity\Sector $sector
     * @return BrandRubizz
     */
    public function setSector(\Web\WebBundle\Entity\Sector $sector = null)
    {
        $this->sector = $sector;

        return $this;
    }

    /**
     * Get sector
     *
     * @return \Web\WebBundle\Entity\Sector
     */
    public function getSector()
    {
        return $this->sector;
    }
}
