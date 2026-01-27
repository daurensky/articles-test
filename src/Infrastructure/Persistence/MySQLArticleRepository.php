<?php

namespace App\Infrastructure\Persistence;

use PDO;
use DateTime;
use Exception;
use App\Domain\Entity\Article;
use App\Domain\Entity\Category;
use App\Domain\Repository\ArticleRepositoryInterface;

class MySQLArticleRepository implements ArticleRepositoryInterface
{
    public function __construct(
        private readonly PDO $pdo,
    )
    {
    }

    /**
     * @throws Exception
     */
    public function findCategoriesWithArticles(int $maxArticlesCount = 3): array
    {
        $sql = "
            SELECT * FROM (
                SELECT 
                    c.id as cat_id,
                    c.name as cat_name,
                    a.id as art_id,
                    a.name as art_name,
                    a.description as art_description,
                    a.image_url,
                    a.created_at,
                    a.views_count,
                    ROW_NUMBER() OVER (PARTITION BY c.id ORDER BY a.created_at DESC) as ra
                FROM categories c
                INNER JOIN article_category ac ON c.id = ac.category_id
                INNER JOIN articles a ON ac.article_id = a.id
            ) as ranked_articles
            WHERE ra <= :maxArticlesCount
            ORDER BY cat_name, created_at DESC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':maxArticlesCount', $maxArticlesCount, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $categories = [];
        foreach ($rows as $row) {
            $catId = $row['cat_id'];

            if (!isset($categories[$catId])) {
                $categories[$catId] = new Category(
                    id: $catId,
                    name: $row['cat_name'],
                    description: ''
                );
            }

            $article = new Article(
                id: $row['art_id'],
                imageUrl: $row['image_url'],
                name: $row['art_name'],
                description: $row['art_description'],
                content: '',
                viewsCount: $row['views_count'],
                categoryIds: [],
                createdAt: new DateTime($row['created_at']),
            );

            $currentArticles = $categories[$catId]->getLatestArticles();
            $currentArticles[] = $article;
            $categories[$catId]->setLatestArticles($currentArticles);
        }

        return array_values($categories);
    }

    /**
     * @throws Exception
     */
    public function findByCategory(int $categoryId, string $sort = 'published', int $limit = 0, int $offset = 0): array
    {
        $allowedSort = ['publish' => 'created_at', 'views' => 'views_count'];
        $orderBy = $allowedSort[$sort] ?? 'created_at';

        $sql = "
            SELECT
                a.id,
                a.name,
                a.description,
                a.image_url,
                a.created_at,
                a.views_count
            FROM articles a
            INNER JOIN article_category ac ON a.id = ac.article_id
            WHERE ac.category_id = :catId
            ORDER BY $orderBy DESC
            LIMIT :limit
            OFFSET :offset
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':catId', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll();

        $articles = [];
        foreach ($rows as $row) {
            $articles[] = new Article(
                id: $row['id'],
                imageUrl: $row['image_url'],
                name: $row['name'],
                description: $row['description'],
                content: '',
                viewsCount: $row['views_count'],
                categoryIds: [],
                createdAt: new DateTime($row['created_at'])
            );
        }

        return $articles;
    }

    public function countByCategory(int $categoryId): int
    {
        $sql = "
            SELECT COUNT(*)
            FROM article_category
            WHERE category_id = :catId
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':catId', $categoryId);
        $stmt->execute();

        return (int)$stmt->fetchColumn();
    }

    /**
     * @throws Exception
     */
    public function findById(int $articleId): Article
    {
        $sql = "
            SELECT
                a.id,
                a.image_url,
                a.name,
                a.description,
                a.content,
                a.views_count,
                a.created_at,
                GROUP_CONCAT(ac.category_id) as category_ids
            FROM articles a
            LEFT JOIN article_category ac ON a.id = ac.article_id
            WHERE a.id = :artId
            GROUP BY a.id
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':artId', $articleId);
        $stmt->execute();
        $row = $stmt->fetch();

        $categoryIds = $row['category_ids'] ? explode(',', $row['category_ids']) : [];

        return new Article(
            id: $row['id'],
            imageUrl: $row['image_url'],
            name: $row['name'],
            description: $row['description'],
            content: $row['content'],
            viewsCount: $row['views_count'],
            categoryIds: $categoryIds,
            createdAt: new DateTime($row['created_at'])
        );
    }

    /**
     * @throws Exception
     */
    public function findRelated(int $articleId, array $categoryIds, int $limit = 3): array
    {
        if (empty($categoryIds)) return [];

        $placeholders = implode(',', array_fill(0, count($categoryIds), '?'));

        $sql = "
            SELECT DISTINCT
                a.id,
                a.image_url,
                a.name,
                a.description,
                a.views_count,
                a.created_at
            FROM articles a
            INNER JOIN article_category ac ON a.id = ac.article_id
            WHERE ac.category_id IN ($placeholders)
                  AND a.id != ? 
            ORDER BY a.created_at DESC 
            LIMIT ?
        ";

        $stmt = $this->pdo->prepare($sql);

        $index = 1;
        foreach ($categoryIds as $catId) {
            $stmt->bindValue($index++, $catId, PDO::PARAM_INT);
        }
        $stmt->bindValue($index++, $articleId, PDO::PARAM_INT);
        $stmt->bindValue($index, $limit, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll();

        $articles = [];
        foreach ($rows as $row) {
            $articles[] = new Article(
                id: $row['id'],
                imageUrl: $row['image_url'],
                name: $row['name'],
                description: $row['description'],
                content: '',
                viewsCount: $row['views_count'],
                categoryIds: [],
                createdAt: new DateTime($row['created_at'])
            );
        }

        return $articles;
    }
}