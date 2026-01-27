<?php

namespace App\Infrastructure;

use PDO;

class Seeder
{
    public function __construct(
        private readonly PDO $pdo
    )
    {
    }

    public function run(): void
    {
        $this->pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");
        $this->pdo->exec("TRUNCATE TABLE article_category;");
        $this->pdo->exec("TRUNCATE TABLE articles;");
        $this->pdo->exec("TRUNCATE TABLE categories;");
        $this->pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");

        $categories = ['Программирование', 'Спорт', 'Компьютеры', 'Экономика'];
        $catIds = [];
        $stmt = $this->pdo->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");

        foreach ($categories as $name) {
            $stmt->execute([$name, "Все свежие новости из мира $name"]);
            $catIds[] = $this->pdo->lastInsertId();
        }

        $stmtNews = $this->pdo->prepare("
            INSERT INTO articles (name, description, content, image_url, views_count, created_at) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmtPivot = $this->pdo->prepare("INSERT INTO article_category (article_id, category_id) VALUES (?, ?)");

        for ($i = 1; $i <= 50; $i++) {
            $date = date('Y-m-d H:i:s', strtotime("-$i days"));
            $stmtNews->execute([
                "Заголовок новости №$i",
                "Современные технологии разработки программного обеспечения позволяют создавать гибкие и масштабируемые системы. Использование паттернов проектирования и чистой архитектуры упрощает поддержку кода, снижая затраты на развитие и поиск ошибок в бизнес-логике.",
                "Разработка современных информационных систем требует от программиста не только знания синтаксиса выбранного языка, но и глубокого понимания принципов проектирования. Одним из наиболее эффективных подходов в этой области является предметно-ориентированное проектирование, которое позволяет сфокусироваться на бизнес-логике приложения. В рамках данной методологии программный код разделяется на несколько независимых слоев, каждый из которых выполняет строго определенную роль. Уровень предметной области содержит в себе основные сущности и правила, которые остаются неизменными при смене технологического стека. Слой инфраструктуры берет на себя задачи по взаимодействию с внешними ресурсами, такими как базы данных или сторонние сервисы. Презентационный уровень отвечает за отображение информации конечному пользователю. Такой подход обеспечивает высокую гибкость и масштабируемость системы в долгосрочной перспективе. Грамотное разделение ответственности делает код читаемым и значительно упрощает процесс его тестирования, что критически важно для коммерческих продуктов высокого уровня качества.",
                "https://picsum.photos/seed/$i/800/600",
                rand(0, 5000),
                $date,
            ]);

            $newsId = $this->pdo->lastInsertId();

            $randomCats = (array)array_rand(array_flip($catIds), rand(1, 2));
            foreach ($randomCats as $catId) {
                $stmtPivot->execute([$newsId, $catId]);
            }
        }
    }
}