<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(
 * fields={"email"},
 * message="Un autre utilisateur s'est déjà inscrit avec cette adresse email, merci de la modifier"
 * )
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Vous devez renseigner votre prénom")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Vous devez renseigner votre nom de famille")
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(message="Veuillez renseigner un email valide !")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Url(message="Veuillez donner une URL valide pour votre avatar !")
     */
    private $picture;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $hash;


    /**
     *
     * @Assert\EqualTo(propertyPath="hash",message="Vous n'avez pas correctement confirmé votre mot de passe !")
     * @var [type]
     */
    // Pour la Confirmation de mot de passe 2 eme champs de form
    public $passwordConfirm;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=10, minMessage="Votre introduction doit faire au moins 10 caractères")
     */
    private $introduction;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(min=100, minMessage="Votre description doit faire au moins 100 caractères")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity=Bijoux::class, mappedBy="author")
     */
    private $bijouxs;

    /**
     * @ORM\OneToMany(targetEntity=Couture::class, mappedBy="author")
     */
    private $coutures;

    /**
     * @ORM\ManyToMany(targetEntity=Role::class, mappedBy="users")
     */
    private $userRoles;

    /**
     * @ORM\OneToMany(targetEntity=CommentB::class, mappedBy="author", orphanRemoval=true)
     */
    private $commentBs;

    /**
     * @ORM\OneToMany(targetEntity=CommentC::class, mappedBy="author", orphanRemoval=true)
     */
    private $commentCs;


    
    public function getFullName() {
        // Renvoi une concatenation de nom et prenom
        return "{$this->firstName} {$this->lastName}";
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
            $this->slug = $slugify->slugify($this->firstName . ' ' . $this->lastName);
        }
    }


    public function __construct()
    {
        $this->bijouxs = new ArrayCollection();
        $this->coutures = new ArrayCollection();
        $this->userRoles = new ArrayCollection();
        $this->commentBs = new ArrayCollection();
        $this->commentCs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    /**
     * @return Collection|Bijoux[]
     */
    public function getBijouxs(): Collection
    {
        return $this->bijouxs;
    }

    public function addBijoux(Bijoux $bijoux): self
    {
        if (!$this->bijouxs->contains($bijoux)) {
            $this->bijouxs[] = $bijoux;
            $bijoux->setAuthor($this);
        }

        return $this;
    }

    public function removeBijoux(Bijoux $bijoux): self
    {
        if ($this->bijouxs->removeElement($bijoux)) {
            // set the owning side to null (unless already changed)
            if ($bijoux->getAuthor() === $this) {
                $bijoux->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Couture[]
     */
    public function getCoutures(): Collection
    {
        return $this->coutures;
    }

    public function addCouture(Couture $couture): self
    {
        if (!$this->coutures->contains($couture)) {
            $this->coutures[] = $couture;
            $couture->setAuthor($this);
        }

        return $this;
    }

    public function removeCouture(Couture $couture): self
    {
        if ($this->coutures->removeElement($couture)) {
            // set the owning side to null (unless already changed)
            if ($couture->getAuthor() === $this) {
                $couture->setAuthor(null);
            }
        }

        return $this;
    }

    public function getRoles()
    {
        $roles = $this->userRoles->map(function($role){
            return $role->getTitle();
        })->toArray();

        $roles[] = 'ROLE_USER';

        return $roles;
        // $roles = $this->roles;
        // // guarantee every user at least has ROLE_USER
        // $roles[] = 'ROLE_USER';

        // return array_unique($roles);
    }

    public function getPassword(): string
    {
        return $this->hash;
    }

    public function getSalt()
    {
    }

    public function getUsername()
    {
        return $this->email;
    }

    /**
     * The public representation of the user (e.g. a username, an email address, etc.)
     *
     * UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function eraseCredentials()
    {
    }

    /**
     * @return Collection|Role[]
     */
    public function getUserRoles(): Collection
    {
        return $this->userRoles;
    }

    public function addUserRole(Role $userRole): self
    {
        if (!$this->userRoles->contains($userRole)) {
            $this->userRoles[] = $userRole;
            $userRole->addUser($this);
        }

        return $this;
    }

    public function removeUserRole(Role $userRole): self
    {
        if ($this->userRoles->removeElement($userRole)) {
            $userRole->removeUser($this);
        }

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
            $commentB->setAuthor($this);
        }

        return $this;
    }

    public function removeCommentB(CommentB $commentB): self
    {
        if ($this->commentBs->removeElement($commentB)) {
            // set the owning side to null (unless already changed)
            if ($commentB->getAuthor() === $this) {
                $commentB->setAuthor(null);
            }
        }

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
            $commentC->setAuthor($this);
        }

        return $this;
    }

    public function removeCommentC(CommentC $commentC): self
    {
        if ($this->commentCs->removeElement($commentC)) {
            // set the owning side to null (unless already changed)
            if ($commentC->getAuthor() === $this) {
                $commentC->setAuthor(null);
            }
        }

        return $this;
    }
}
