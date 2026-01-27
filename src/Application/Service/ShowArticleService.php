<?php

namespace App\Application\Service;

use App\Domain\Repository\ArticleRepositoryInterface;

class ShowArticleService
{
    public function __construct(
        private readonly ArticleRepositoryInterface $articleRepository
    )
    {
    }

    public function execute(int $articleId): array
    {
        $article = $this->articleRepository->findById($articleId);
        $relatedArticles = $this->articleRepository->findRelated($article->getId(), $article->getCategoryIds());

        return [
            'article'         => $article,
            'relatedArticles' => $relatedArticles,
        ];
    }
}