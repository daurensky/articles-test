<?php

namespace App\Domain\Entity;

class Category
{
    private array $latestArticles = [];

    public function __construct(
        private readonly ?int   $id,
        private readonly string $name,
        private readonly string $description,
    )
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getLatestArticles(): array
    {
        return $this->latestArticles;
    }

    public function setLatestArticles(array $latestArticles): void
    {
        $this->latestArticles = $latestArticles;
    }
}