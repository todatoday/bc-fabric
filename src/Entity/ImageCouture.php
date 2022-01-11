<?php

namespace App\Entity;

use App\Repository\ImageCoutureRepository;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ImageCoutureRepository::class)
 */
class ImageCouture
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Url()
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=10, minMessage="Le titre de l'image doit faire au moins 10 caractères")
     */
    private $caption;

    /**
     * @ORM\ManyToOne(targetEntity=Couture::class, inversedBy="imageCoutures")
     * @ORM\JoinColumn(nullable=false)
     */
    private $couture;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getCaption(): ?string
    {
        return $this->caption;
    }

    public function setCaption(string $caption): self
    {
        $this->caption = $caption;

        return $this;
    }

    public function getCouture(): ?Couture
    {
        return $this->couture;
    }

    public function setCouture(?Couture $couture): self
    {
        $this->couture = $couture;

        return $this;
    }
}
