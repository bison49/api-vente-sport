<?php

namespace App\Entity;

use App\Repository\ImageArticleRepository;
use ApiPlatform\Metadata\ApiResource;
use App\Controller\ImageController;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImageArticleRepository::class)]
#[ApiResource(operations: [new Get(),
new GetCollection(),
new Post(controller: ImageController::class, name: 'app-add-images'),
])]
class ImageArticle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['article', 'user-advert'])]
    private ?string $uri = null;

    #[ORM\ManyToOne(inversedBy: 'images', cascade:["persist"])]
    private ?Article $article = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUri(): ?string
    {
        return $this->uri;
    }

    public function setUri(string $uri): self
    {
        $this->uri = $uri;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): self
    {
        $this->article = $article;

        return $this;
    }
}
