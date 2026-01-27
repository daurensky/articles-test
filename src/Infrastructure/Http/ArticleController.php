<?php

namespace App\Infrastructure\Http;

use Smarty\Exception;
use App\Application\Service\ShowArticleService;

class ArticleController extends AbstractController
{
    public function __construct(
        private readonly ShowArticleService $showArticleService,
    )
    {
        parent::__construct();
    }

    /**
     * @throws Exception
     */
    public function show(string $id): void
    {
        $this->render(
            'article.tpl',
            $this->showArticleService->execute((int)$id)
        );
    }
}