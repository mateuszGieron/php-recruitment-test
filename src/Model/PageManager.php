<?php

namespace Snowdog\DevTest\Model;

use Snowdog\DevTest\Core\Database;

class PageManager
{

    /**
     * @var Database|\PDO
     */
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function getAllByWebsite(Website $website)
    {
        $websiteId = $website->getWebsiteId();
        /** @var \PDOStatement $query */
        $query = $this->database->prepare('SELECT * FROM pages WHERE website_id = :website');
        $query->bindParam(':website', $websiteId, \PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(\PDO::FETCH_CLASS, Page::class);
    }

    public function create(Website $website, $url)
    {
        $websiteId = $website->getWebsiteId();
        /** @var \PDOStatement $statement */
        $statement = $this->database->prepare('INSERT INTO pages (url, website_id) VALUES (:url, :website)');
        $statement->bindParam(':url', $url, \PDO::PARAM_STR);
        $statement->bindParam(':website', $websiteId, \PDO::PARAM_INT);
        $statement->execute();
        return $this->database->lastInsertId();
    }

    /**
     * Update last visit of page
     *
     * @param int       $pageId
     * @param string    $dateTime
     *
     * @return void
     */
    public function updateLastVisitTime(int $pageId, string $dateTime): void
    {
        /** @var \PDOStatement $statement */
        $statement = $this->database->prepare(
            'UPDATE pages SET last_visit = :dateTime WHERE page_id = :pageId'
        );
        $statement->bindParam(':dateTime', $dateTime, \PDO::PARAM_STR);
        $statement->bindParam(':pageId', $pageId, \PDO::PARAM_INT);
        $statement->execute();
    }
}