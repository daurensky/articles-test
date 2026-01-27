<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Article;

interface ArticleRepositoryInterface
{
    /**
     * @return Article[]
     */
    public function findCategoriesWithArticles(int $maxArticlesCount = 3): array;

    /**
     * @return Article[]
     */
    public function findByCategory(int $categoryId, string $sort = 'published', int $limit = 0, int $offset = 0): array;

    public function countByCategory(int $categoryId): int;

    public function findById(int $articleId): Article;

    /**
     * @return Article[]
     */
    public function findRelated(int $articleId, array $categoryIds, int $limit = 3): array;
}