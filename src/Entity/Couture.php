<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CoutureRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass=CoutureRepository::class)
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(
 *  fields={"title"},
 *  message="Un autre produit possède déjà ce titre, merci de le modifier"
 * )
 */
class Couture
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=10, max=255, 
     *  minMessage="Le titre doit faire plus de 10 caractères",
     *  maxMessage="Le titre ne peut pas faire  plus de  255 caractères !")
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(min=20, minMessage="Votre introduction doit faire plus de 20 caractères")
     */
    private $introduction;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(min=100, minMessage="Votre description ne doit pas faire plus de 100 caractères")
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Url()
     */
    private $coverImage;

    /**
     * @ORM\OneToMany(targetEntity=ImageCouture::class, mappedBy="couture", orphanRemoval=true)
     * @Assert\Valid()
     */
    private $imageCoutures;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="coutures")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity=CommentC::class, mappedBy="couture", orphanRemoval=true)
     */
    private $commentCs;

    public function __construct()
    {
        $this->imageCoutures = new ArrayCollection();
        $this->commentCs = new ArrayCollection();
    }

    /**
     * Permet d'initialiser le Slug !
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     *
     * @return void
     */
    public function initializeSlug()
    {
        if (empty($this->slug)) {
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->title);
        }
    }


    /**
     * Permet de récupérer le commentaire d'un auteur par rapport à un produit Bijoux
     *
     * @param User $author
     * @return CommentC|null
     */
    public function getCommentCFromAuthor(User $author)
    {
        // on boucle sur le tableau de commentaires pour chaque commentaire que j'ai dans cette produit
        foreach ($this->commentCs as $commentC) {
            // si l'auteur de commentaire se le meme que ici alors je veut returne ce commentaire
            if ($commentC->getAuthor() === $author) return $commentC;
        }
        // si non on returne null veut que il a pas encore commenter ce produit
        return null;
    }

    /**
     * Permet de faire la moyenne de notes reçu 
     *
     * @return void
     */
    public function getAvgRatings()
    {
        // Calculer la somme des notations
        $sum = array_reduce($this->commentCs->toArray(), function ($total, $commentC) {
            return $total + $commentC->getRating();
        }, 0);

        //  Faire le division pour avoir la moyenne
        if (count($this->commentCs) > 0) return $sum / count($this->commentCs);

        return 0;
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getIntroduction(): ?string
    {
        return $this->introduction;
    }

    public function setIntroduction(string $introduction): self
    {
        $this->introduction = $introduction;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCoverImage(): ?string
    {
        return $this->coverImage;
    }

    public function setCoverImage(string $coverImage): self
    {
        $this->coverImage = $coverImage;

        return $this;
    }

    /**
     * @return Collection|ImageCouture[]
     */
    public function getImageCoutures(): Collection
    {
        return $this->imageCoutures;
    }

    public function addImageCouture(ImageCouture $imageCouture): self
    {
        if (!$this->imageCoutures->contains($imageCouture)) {
            $this->imageCoutures[] = $imageCouture;
            $imageCouture->setCouture($this);
        }

        return $this;
    }

    public function removeImageCouture(ImageCouture $imageCouture): self
    {
        if ($this->imageCoutures->removeElement($imageCouture)) {
            // set the owning side to null (unless already changed)
            if ($imageCouture->getCouture() === $this) {
                $imageCouture->setCouture(null);
            }
        }

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|CommentC[]
     */
    public function getCommentCs(): Collection
    {
        return $this->commentCs;
    }

    public function addCommentC(CommentC $commentC): self
    {
        if (!$this->commentCs->contains($commentC)) {
            $this->commentCs[] = $commentC;
            $commentC->setCouture($this);
        }

        return $this;
    }

    public function removeCommentC(CommentC $commentC): self
    {
        if ($this->commentCs->removeElement($commentC)) {
            // set the owning side to null (unless already changed)
            if ($commentC->getCouture() === $this) {
                $commentC->setCouture(null);
            }
        }

        return $this;
    }
}
