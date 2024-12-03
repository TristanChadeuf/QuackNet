<?php

namespace App\Entity;

use AllowDynamicProperties;
use App\Repository\CoinCoinRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CoinCoinRepository::class)]

class CoinCoin
{
    public function __construct()
    {
        $this->created_at = new \DateTime();
    }
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private ?string $Post = null;



    #[ORM\Column(length: 255)]
    private ?string $Picture = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\Column(length: 50)]
    private ?string $Tags = null;

    #[ORM\ManyToOne(inversedBy: 'coinCoins')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Duck $author = null;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPost(): ?string
    {
        return $this->Post;
    }

    public function setPost(string $Post): static
    {
        $this->Post = $Post;

        return $this;
    }

    public function getDuckName(): ?string
    {
        return $this->DuckName;
    }

    public function setDuckName(string $DuckName): static
    {
        $this->DuckName = $DuckName;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->Picture;
    }

    public function setPicture(string $Picture): static
    {
        $this->Picture = $Picture;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }


    public function getTags(): ?string
    {
        return $this->Tags;
    }

    public function setTags(string $Tags): static
    {
        $this->Tags = $Tags;

        return $this;
    }

    public function getAuthor(): ?Duck
    {
        return $this->author;
    }

    public function setAuthor(?Duck $author): static
    {
        $this->author = $author;

        return $this;
    }
}
