<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Doctrine\Orm\Filter\NumericFilter;
use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
#[ApiResource(order: ['dateForSale' => 'DESC'],
operations: [new Get(normalizationContext: ['groups' => ['article']]),
new Post(),
new Delete(),
new GetCollection(normalizationContext: ['groups' => ['article']]),
new Patch(denormalizationContext: ['groups' => ['article']]),
])]
#[ApiFilter(SearchFilter::class, properties: ['name' => 'partial', 'categorie.name' => 'exact', 'seller.userInfo.adress.city' => 'exact', 'seller.userInfo.adress.postCode' => 'exact'])]
#[ApiFilter(RangeFilter::class, properties: ['price'])]
#[ApiFilter(DateFilter::class, properties: ['dateSold'])]
#[ApiFilter(OrderFilter::class, properties: ['categorie.name' => 'ASC'])]
#[ApiFilter(NumericFilter::class, properties: ['seller.id', 'buyer.id'])]
#[ApiFilter(BooleanFilter::class, properties: ['isSold'])]
#[ApiFilter(OrderFilter::class, properties: ['price', 'dateForSale'], arguments: ['orderParameterName' => 'order'])]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['article', 'user-advert'])]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    #[Groups(['article', 'user-advert'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups('article')]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['article', 'user-advert'])]
    private ?int $price = null;

    #[ORM\Column]
    #[Groups(['article', 'user-advert'])]
    private ?bool $isSold = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['article', 'user-advert'])]
    private ?\DateTimeInterface $dateForSale = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups('article')]
    private ?\DateTimeInterface $dateSold = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['article', 'user-advert'])]
    private ?Categories $categorie = null;

    #[ORM\OneToMany(mappedBy: 'article', targetEntity: ImageArticle::class, cascade:["persist", "remove"])]
    #[Groups(['article', 'user-advert'])]
    private Collection $images;

    #[ORM\ManyToOne(inversedBy: 'purchasedArticles')]
    #[Groups('article')]
    private ?User $buyer = null;

    #[ORM\ManyToOne(inversedBy: 'soldArticles')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('article')]
    private ?User $seller = null;

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function isIsSold(): ?bool
    {
        return $this->isSold;
    }

    public function setIsSold(bool $isSold): self
    {
        $this->isSold = $isSold;

        return $this;
    }

    public function getDateForSale(): ?\DateTimeInterface
    {
        return $this->dateForSale;
    }

    public function setDateForSale(\DateTimeInterface $dateForSale): self
    {
        $this->dateForSale = $dateForSale;

        return $this;
    }

    public function getDateSold(): ?\DateTimeInterface
    {
        return $this->dateSold;
    }

    public function setDateSold(?\DateTimeInterface $dateSold): self
    {
        $this->dateSold = $dateSold;

        return $this;
    }

    public function getCategorie(): ?Categories
    {
        return $this->categorie;
    }

    public function setCategorie(?Categories $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return Collection<int, ImageArticle>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(ImageArticle $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setArticle($this);
        }

        return $this;
    }

    public function removeImage(ImageArticle $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getArticle() === $this) {
                $image->setArticle(null);
            }
        }

        return $this;
    }

    public function getBuyer(): ?User
    {
        return $this->buyer;
    }

    public function setBuyer(?User $buyer): self
    {
        $this->buyer = $buyer;

        return $this;
    }

    public function getSeller(): ?User
    {
        return $this->seller;
    }

    public function setSeller(?User $seller): self
    {
        $this->seller = $seller;

        return $this;
    }
}
