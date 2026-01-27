<?php

namespace App\Infrastructure\Http;

use Smarty\Exception;
use App\Domain\Repository\ArticleRepositoryInterface;

class WelcomeController extends AbstractController
{
    public function __construct(
        private readonly ArticleRepositoryInterface $articleRepository
    )
    {
        parent::__construct();
    }

    /**
     * @throws Exception
     */
    public function index(): void
    {
        $categories = $this->articleRepository->findCategoriesWithArticles();
        $this->render(
            'welcome.tpl',
            compact('categories')
        );
    }
}