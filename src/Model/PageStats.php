<?php

namespace Snowdog\DevTest\Model;

use Snowdog\DevTest\Core\Database;

class PageStats
{
    /**
     * @var Database|\PDO
     */
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    /**
     * Get total of customer pages
     *
     * @param int $userId
     *
     * @return int
     */
    public function getTotal(int $userId): int
    {
        /** @var \PDOStatement $statement */
        $statement = $this->database->prepare(
            'SELECT
            COUNT(b.page_id) as total
            FROM websites a 
            LEFT JOIN pages b ON a.website_id = b.website_id
            WHERE user_id = :userId'
        );
        $statement->bindParam(':userId', $userId, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchColumn();
    }

    /**
     * Get most recently visited page by customer ID
     *
     * @param int $userId
     *
     * @return array
     */
    public function getMostRecentlyVisited(int $userId): array
    {
        /** @var \PDOStatement $statement */
        $statement = $this->database->prepare(
            'SELECT
            a.hostname, b.url, b.last_visit
            FROM websites a 
            LEFT JOIN pages b ON a.website_id = b.website_id
            WHERE user_id = :userId
            ORDER BY b.last_visit DESC
            LIMIT 1'
        );
        $statement->bindParam(':userId', $userId, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Get least recently visited page by customer ID
     *
     * @param int $userId
     *
     * @return array
     */
    public function getLeastRecentlyVisited(int $userId): array
    {
        /** @var \PDOStatement $statement */
        $statement = $this->database->prepare(
            'SELECT
            a.hostname, b.url, b.last_visit
            FROM websites a 
            LEFT JOIN pages b ON a.website_id = b.website_id
            WHERE user_id = :userId
            ORDER BY b.last_visit ASC
            LIMIT 1'
        );
        $statement->bindParam(':userId', $userId, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetch(\PDO::FETCH_ASSOC);
    }
}
