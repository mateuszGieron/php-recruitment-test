<?php

namespace Snowdog\DevTest\Controller;

use Snowdog\DevTest\Model\UserManager;
use Snowdog\DevTest\Model\VarnishManager;

class CreateVarnishAction
{
    /**
     * @var VarnishManager
     */
    private $varnishManager;

    /**
     * @var UserManager
     */
    private $userManager;

    /** @var \Snowdog\DevTest\Model\User $user */
    private $user;

    public function __construct(UserManager $userManager, VarnishManager $varnishManager)
    {
        $this->varnishManager = $varnishManager;
        $this->userManager = $userManager;

        if (isset($_SESSION['login'])) {
            $this->user = $userManager->getByLogin($_SESSION['login']);
        }
    }

    public function execute()
    {
        if ($this->user) {
            $ip = $_POST['ip'];
            $userId = $this->user->getUserId();

            if (empty($ip)) {
                $_SESSION['flash'] = 'IP cannot be empty!';
            } elseif ($this->varnishManager->create($this->user, $ip)) {
                $_SESSION['flash'] = 'Varnish Server ' . $ip . ' added!';
            }
        }

        header('Location: /varnishes');
    }
}