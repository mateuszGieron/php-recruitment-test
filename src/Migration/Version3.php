<?php

namespace Snowdog\DevTest\Migration;

use Snowdog\DevTest\Core\Database;
use Snowdog\DevTest\Model\PageManager;
use Snowdog\DevTest\Model\UserManager;
use Snowdog\DevTest\Model\WebsiteManager;

class Version3
{
    /**
     * @var Database|\PDO
     */
    private $database;
    /**
     * @var UserManager
     */
    private $userManager;
    /**
     * @var WebsiteManager
     */
    private $websiteManager;
    /**
     * @var PageManager
     */
    private $pageManager;

    public function __construct(
        Database $database
    ) {
        $this->database = $database;
    }

    public function __invoke()
    {
        $this->alterTablePages();
    }

    /**
     * Add last visit time column to `pages` table
     *
     * @return void
     */
    private function alterTablePages(): void
    {
        $createQuery = <<<SQL
ALTER TABLE `pages` ADD `last_visit` DATETIME;
SQL;
        $this->database->exec($createQuery);
    }
}