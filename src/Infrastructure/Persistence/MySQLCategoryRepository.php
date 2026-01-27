<?php

namespace App\Infrastructure\Persistence;

use PDO;
use App\Domain\Entity\Category;
use App\Domain\Repository\CategoryRepositoryInterface;

class MySQLCategoryRepository implements CategoryRepositoryInterface
{
    public function __construct(
        private readonly PDO $pdo
    )
    {
    }

    public function findById(int $categoryId): Category
    {
        $sql = "
            SELECT 
                id,
                name,
                description
            FROM categories
            WHERE id = :id
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $categoryId, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return new Category(
            id: $row['id'],
            name: $row['name'],
            description: $row['description']
        );
    }
}