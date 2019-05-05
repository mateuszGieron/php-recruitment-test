<?php

namespace Snowdog\DevTest\Controller;

use Snowdog\DevTest\Model\UserManager;
use Snowdog\DevTest\Model\VarnishManager;

class CreateVarnishLinkAction
{
    /**
     * @var UserManager
     */
    private $userManager;
    /**
     * @var VarnishManager
     */
    private $varnishManager;

    public function __construct(UserManager $userManager, VarnishManager $varnishManager)
    {
        $this->userManager = $userManager;
        $this->varnishManager = $varnishManager;

        if (isset($_SESSION['login'])) {
            $this->user = $userManager->getByLogin($_SESSION['login']);
        }
    }

    public function execute()
    {
        $response = [];
        if ($this->user) {
            $varnishId = $_POST['varnishId'];
            $websiteId = $_POST['websiteId'];

            if (empty($varnishId)) {
                $response['flash'] = 'Varnish server ID cannot be empty!';
            } elseif (empty($websiteId)) {
                $response['flash'] = 'Website ID cannot be empty!';
            } elseif ($this->varnishManager->link($varnishId, $websiteId)) {
                $response['flash'] = 'Website linked to Varnish server!';
            } else {
                $response['flash'] = 'Unable to link!';
            }
        }

        header('Content-type: application/json');
        echo json_encode($response);
    }
}