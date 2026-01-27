<?php

namespace App\Infrastructure\Http;

use Smarty\Exception;
use App\Application\Service\ShowCategoryService;

class CategoryController extends AbstractController
{
    public function __construct(
        private readonly ShowCategoryService $showCategoryService
    )
    {
        parent::__construct();
    }

    /**
     * @throws Exception
     */
    public function show(string $id): void
    {
        $sort = $_GET['sort'] ?? 'publish';
        $page = $_GET['page'] ?? 1;

        $this->render(
            'category.tpl',
            $this->showCategoryService->execute((int)$id, $sort, $page)
        );
    }
}