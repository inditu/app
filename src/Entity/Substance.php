<?php
/**
 * Substance entity.
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Substance.
 *
 * @ORM\Entity(repositoryClass="App\Repository\SubstanceRepository")
 */
class Substance extends AbstractEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * id
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * name
     *
     * @Assert\Type(type="string")
     * @Assert\NotBlank
     * @Assert\Length(min="3",max="25")
     *
     * @var string
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Medicine", mappedBy="substances")
     *
     * medicines
     *
     * @var ArrayCollection
     */
    private $medicines;

    /**
     * __construct.
     */
    public function __construct()
    {
        $this->medicines = new ArrayCollection();
    }

    /**
     * getId.
     *
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * getName.
     *
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * setName.
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Medicine[]
     *
     * getMedicines
     */
    public function getMedicines(): Collection
    {
        return $this->medicines;
    }

    /**
     * addMedicine.
     */
    public function addMedicine(Medicine $medicine): self
    {
        if (!$this->medicines->contains($medicine)) {
            $this->medicines[] = $medicine;
            $medicine->addSubstance($this);
        }

        return $this;
    }

    /**
     * removeMedicine.
     */
    public function removeMedicine(Medicine $medicine): self
    {
        if ($this->medicines->contains($medicine)) {
            $this->medicines->removeElement($medicine);
            $medicine->removeSubstance($this);
        }

        return $this;
    }
}
