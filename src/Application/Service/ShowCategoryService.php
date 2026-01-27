<?php

namespace App\Application\Service;

use App\Domain\Repository\ArticleRepositoryInterface;
use App\Domain\Repository\CategoryRepositoryInterface;

class ShowCategoryService
{
    public function __construct(
        private readonly ArticleRepositoryInterface  $articleRepository,
        private readonly CategoryRepositoryInterface $categoryRepository,
    )
    {
    }

    public function execute(int $categoryId, string $sort, int $page): array
    {
        $perPage = 9;
        $offset = ($page - 1) * $perPage;

        $category = $this->categoryRepository->findById($categoryId);
        $articles = $this->articleRepository->findByCategory($categoryId, $sort, $perPage, $offset);
        $totalArticles = $this->articleRepository->countByCategory($categoryId);

        return [
            'category'   => $category,
            'articles'   => $articles,
            'pagination' => [
                'current' => $page,
                'total'   => ceil($totalArticles / $perPage),
                'sort'    => $sort,
            ],
        ];
    }
}