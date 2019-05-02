<?php

namespace Snowdog\DevTest\Controller;

use Snowdog\DevTest\Model\PageStats;
use Snowdog\DevTest\Model\User;
use Snowdog\DevTest\Model\UserManager;
use Snowdog\DevTest\Model\WebsiteManager;

class IndexAction
{

    /**
     * @var WebsiteManager
     */
    private $websiteManager;

    /**
     * @var PageStats
     */
    private $pageStats;

    /**
     * @var User
     */
    private $user;

    public function __construct(UserManager $userManager, WebsiteManager $websiteManager, PageStats $pageStats)
    {
        $this->websiteManager   = $websiteManager;
        $this->pageStats      = $pageStats;
        if (isset($_SESSION['login'])) {
            $this->user = $userManager->getByLogin($_SESSION['login']);
        }
    }

    protected function getWebsites()
    {
        if($this->user) {
            return $this->websiteManager->getAllByUser($this->user);
        } 
        return [];
    }

    /**
     * Get some pages statistics
     *
     * @return array
     */
    protected function getPagesStatistics(): array
    {
        if ($this->user) {
            return [
                'total'                     => $this->pageStats->getTotal((int) $this->user->getUserId()),
                'most_recently_visited'     => $this->pageStats->getMostRecentlyVisited((int) $this->user->getUserId()),
                'least_recently_visited'    => $this->pageStats->getLeastRecentlyVisited((int) $this->user->getUserId())
            ];
        }
        return [];
    }

    public function execute()
    {
        require __DIR__ . '/../view/index.phtml';
    }
}
