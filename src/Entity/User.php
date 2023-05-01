<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use App\Controller\SecurityController;
use App\Controller\ResetPasswordController;
use App\Repository\UserRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(operations: [new Get(normalizationContext: ['groups' => ['user-advert']],
uriTemplate: '/user-advert/{id}', requirements: ['id' => '\d+']),
new Get(),
new GetCollection(),
new Get(normalizationContext: ['groups' => ['user-password']], uriTemplate: '/user-password/{id}', requirements: ['id' => '\d+']),
new Get(controller: SecurityController::class, name: 'app-me',),
new Post(denormalizationContext: ['groups' => ['user-register']],),
new Post(controller: ResetPasswordController::class, name: 'app-reset-password-email'),
new Patch(normalizationContext: ['groups' => ['user-profile']], denormalizationContext: ['groups' => ['user-profile']],),
new Patch(denormalizationContext: ['groups' => ['patch-password']], uriTemplate: '/update-forgot-password/{id}', requirements: ['id' => '\d+'])])]
#[UniqueEntity(fields: 'email',message: "Cet e-mail est déjà associé à un compte")]
#[UniqueEntity(fields: 'username', message: "Ce nom d'utilisateur est déjà utilisé")]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['article', 'patch-password', 'user-profile', 'user-register'])]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups(['user-profile', 'user-register'])]
    private ?string $email = null;

    #[ORM\Column]
    #[Groups(['user-profile', 'user-register'])]
    private array $roles = [];

    #[ORM\Column(length: 255)]
    #[Groups(['patch-password', 'user-password', 'user-register'])]
    private ?string $password = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[Groups(['article', 'user-advert','user-profile', 'user-register'])]
    private ?UserInfo $userInfo = null;

    #[ORM\Column(length: 50)]
    #[Groups(['article', 'user-advert', 'user-profile', 'user-register'])]
    private ?string $username = null;

    #[ORM\OneToMany(mappedBy: 'buyer', targetEntity: Article::class)]
    private Collection $purchasedArticles;

    #[ORM\OneToMany(mappedBy: 'seller', targetEntity: Article::class)]
    #[Groups('user-advert')]
    private Collection $soldArticles;

    public function __construct()
    {
        $this->purchasedArticles = new ArrayCollection();
        $this->soldArticles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getUserInfo(): ?userInfo
    {
        return $this->userInfo;
    }

    public function setUserInfo(?userInfo $userInfo): self
    {
        $this->userInfo = $userInfo;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getPurchasedArticles(): Collection
    {
        return $this->purchasedArticles;
    }

    public function addPurchasedArticle(Article $purchasedArticle): self
    {
        if (!$this->purchasedArticles->contains($purchasedArticle)) {
            $this->purchasedArticles->add($purchasedArticle);
            $purchasedArticle->setBuyer($this);
        }

        return $this;
    }

    public function removePurchasedArticle(Article $purchasedArticle): self
    {
        if ($this->purchasedArticles->removeElement($purchasedArticle)) {
            // set the owning side to null (unless already changed)
            if ($purchasedArticle->getBuyer() === $this) {
                $purchasedArticle->setBuyer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getSoldArticles(): Collection
    {
        return $this->soldArticles;
    }

    public function addSoldArticle(Article $soldArticle): self
    {
        if (!$this->soldArticles->contains($soldArticle)) {
            $this->soldArticles->add($soldArticle);
            $soldArticle->setSeller($this);
        }

        return $this;
    }

    public function removeSoldArticle(Article $soldArticle): self
    {
        if ($this->soldArticles->removeElement($soldArticle)) {
            // set the owning side to null (unless already changed)
            if ($soldArticle->getSeller() === $this) {
                $soldArticle->setSeller(null);
            }
        }

        return $this;
    }
}
