<?php

use App\Domain\Repository\ArticleRepositoryInterface;
use App\Domain\Repository\CategoryRepositoryInterface;
use App\Infrastructure\Persistence\MySQLArticleRepository;
use App\Infrastructure\Persistence\MySQLCategoryRepository;
use function DI\autowire;

return [
    ArticleRepositoryInterface::class  => autowire(MySQLArticleRepository::class),
    CategoryRepositoryInterface::class => autowire(MySQLCategoryRepository::class),
    PDO::class                         => function () {
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s',
            $_ENV['DB_HOST'],
            $_ENV['DB_PORT'],
            $_ENV['DB_DATABASE']
        );
        $user = $_ENV['DB_USERNAME'];
        $pass = $_ENV['DB_PASSWORD'];

        return new PDO($dsn, $user, $pass);
    },
];