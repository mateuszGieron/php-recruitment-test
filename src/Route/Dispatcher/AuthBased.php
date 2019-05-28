<?php
/**
 * AuthBased Dispatcher
 */

namespace Snowdog\DevTest\Route\Dispatcher;

use Snowdog\DevTest\Model\UserManager;
use Snowdog\DevTest\Core\Database;

class AuthBased extends \FastRoute\Dispatcher\GroupCountBased
{
    const FORBIDDEN = 403;
    const UNAUTHORIZED = 401;

    /**
     * @param string $httpMethod
     * @param string $uri
     *
     * @return array
     */
    public function dispatch($httpMethod, $uri)
    {
        $userManager = new UserManager(new Database());
        if (isset($_SESSION['login'])) {
            $user = $userManager->getByLogin($_SESSION['login']);
        }

        if (isset($this->variableRouteData[$httpMethod])) {
            foreach ($this->variableRouteData[$httpMethod] as $routeKey => $route) {
                $regex = $route['regex'];
                if (!preg_match($regex, $uri, $matches)) {
                    continue;
                }

                if ($this->variableRouteData[$httpMethod][$routeKey]['routeMap'][count($matches)][0][2] === true
                    && !$user) {
                    return [self::UNAUTHORIZED];
                } elseif ($this->variableRouteData[$httpMethod][$routeKey]['routeMap'][count($matches)][0][2] === false
                    && isset($user)) {
                    return [self::FORBIDDEN];
                }

                //Needed for handle callable
                unset($this->variableRouteData[$httpMethod][$routeKey]['routeMap'][count($matches)][0][2]);
                break;
            }
        }

        if (isset($this->staticRouteMap[$httpMethod][$uri][2])) {
            if ($this->staticRouteMap[$httpMethod][$uri][2] === true && !$user) {
                return [self::UNAUTHORIZED];
            } elseif ($this->staticRouteMap[$httpMethod][$uri][2] === false && isset($user)) {
                return [self::FORBIDDEN];
            }

            //Needed for handle callable
            unset($this->staticRouteMap[$httpMethod][$uri][2]);
        }
        return parent::dispatch($httpMethod, $uri);
    }
}
