<?php

namespace App\Entity;

use App\Entity\User;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\BijouxRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=BijouxRepository::class)
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(
 *  fields={"title"},
 *  message="Un autre produit possède déjà ce titre, merci de le modifier"
 * )
 */
class Bijoux
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
     * @ORM\OneToMany(targetEntity=ImageBijoux::class, mappedBy="bijoux", orphanRemoval=true)
     */
    private $imageBijouxs;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="bijouxs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity=CommentB::class, mappedBy="bijoux", orphanRemoval=true)
     */
    private $commentBs;

    public function __construct()
    {
        $this->imageBijouxs = new ArrayCollection();
        $this->commentBs = new ArrayCollection();
    }

    /**
     * Permet d'initialiser le Slug !
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * @return void
     */
    public function initializeSlug()
    {
        if (empty($this->slug)) {
            $slugify =  new Slugify();
            $this->slug = $slugify->slugify($this->title);
        }
    }


    /**
     * Permet de récupérer le commentaire d'un auteur par rapport à un produit Bijoux
     *
     * @param User $author
     * @return CommentB|null
     */
    public function getCommentBFromAuthor(User $author)
    {
        // on boucle sur le tableau de commentaires pour chaque commentaire que j'ai dans cette produit
        foreach ($this->commentBs as $commentB) {
            // si l'auteur de commentaire se le meme que ici alors je veut returne ce commentaire
            if ($commentB->getAuthor() === $author) return $commentB;
        }
        // si non on returne null veut que il a pas encore commenter ce produit
        return null;
    }


    /**
     * Permet de obtenir la moyenne globale de notes reçu pour ce produit
     *
     * @return float
     */
    public function getAvgRatings()
    {
        // Calculer la somme des notations
        $sum = array_reduce($this->commentBs->toArray(), function ($total, $commentB) {
            return $total + $commentB->getRating();
        }, 0);

        //  Faire le division pour avoir la moyenne
        if (count($this->commentBs) > 0) return $sum / count($this->commentBs);

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
     * @return Collection|ImageBijoux[]
     */
    public function getImageBijouxs(): Collection
    {
        return $this->imageBijouxs;
    }

    public function addImageBijoux(ImageBijoux $imageBijoux): self
    {
        if (!$this->imageBijouxs->contains($imageBijoux)) {
            $this->imageBijouxs[] = $imageBijoux;
            $imageBijoux->setBijoux($this);
        }

        return $this;
    }

    public function removeImageBijoux(ImageBijoux $imageBijoux): self
    {
        if ($this->imageBijouxs->removeElement($imageBijoux)) {
            // set the owning side to null (unless already changed)
            if ($imageBijoux->getBijoux() === $this) {
                $imageBijoux->setBijoux(null);
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
     * @return Collection|CommentB[]
     */
    public function getCommentBs(): Collection
    {
        return $this->commentBs;
    }

    public function addCommentB(CommentB $commentB): self
    {
        if (!$this->commentBs->contains($commentB)) {
            $this->commentBs[] = $commentB;
            $commentB->setBijoux($this);
        }

        return $this;
    }

    public function removeCommentB(CommentB $commentB): self
    {
        if ($this->commentBs->removeElement($commentB)) {
            // set the owning side to null (unless already changed)
            if ($commentB->getBijoux() === $this) {
                $commentB->setBijoux(null);
            }
        }

        return $this;
    }
}
