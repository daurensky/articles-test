<?php

namespace App\Domain\Entity;

use DateTime;

class Article
{
    public function __construct(
        private readonly ?int      $id,
        private readonly string    $imageUrl,
        private readonly string    $name,
        private readonly string    $description,
        private readonly string    $content,
        private readonly int       $viewsCount,
        private readonly array     $categoryIds,
        private readonly ?DateTime $createdAt,
    )
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getViewsCount(): int
    {
        return $this->viewsCount;
    }

    public function getCategoryIds(): array
    {
        return $this->categoryIds;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }
}