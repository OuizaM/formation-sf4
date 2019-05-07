<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CarRepository")
 */
class Car
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="le model ne peux pas etre vide!")
     */
    private $model;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="le prix ne peux pas etre vide!")
     * @Assert\LessThan(value =3000, message="maximum 3000")
     * @Assert\GreaterThan(value =100, message="minimum 100")
     */
    private $price;

    /**
     * @ORM\OneToOne(targetEntity="Image" , cascade={"persist","remove"})
     */

    private $image;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }


    public function getImage(): ?Image
    {

        return $this->image;
    }

    public function setImage($image): void
    {
        $this->image = $image;
    }
}

