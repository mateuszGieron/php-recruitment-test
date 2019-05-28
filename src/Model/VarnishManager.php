<?php

namespace Snowdog\DevTest\Model;

use Snowdog\DevTest\Core\Database;

class VarnishManager
{

    /**
     * @var Database|\PDO
     */
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function getAllByUser(User $user)
    {
        $userId = $user->getUserId();
        /** @var \PDOStatement $query */
        $query = $this->database->prepare('SELECT * FROM varnishes WHERE user_id = :user');
        $query->bindParam(':user', $userId, \PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(\PDO::FETCH_CLASS, Varnish::class);
    }

    /**
     * Get linked websites to varnish
     *
     * @param Varnish $varnish
     *
     * @return array|null
     */
    public function getWebsites(Varnish $varnish): ?array
    {
        $varnishId = $varnish->getVarnishId();
        /** @var \PDOStatement $query */
        $query = $this->database->prepare('SELECT website_id FROM varnishes_websites WHERE varnish_id = :varnish_id');
        $query->bindParam(':varnish_id', $varnishId, \PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function getByWebsite(Website $website)
    {
        // TODO: add logic here
    }

    /**
     * @param User      $user
     * @param string    $ip
     *
     * @return int|null
     */
    public function create(User $user, string $ip): ?int
    {
        $userId = $user->getUserId();
        /** @var \PDOStatement $statement */
        $statement = $this->database->prepare('INSERT INTO varnishes (ip, user_id) VALUES (:ip, :user)');
        $statement->bindParam(':ip', $ip, \PDO::PARAM_STR);
        $statement->bindParam(':user', $userId, \PDO::PARAM_INT);
        $statement->execute();
        return $this->database->lastInsertId();
    }

    /**
     * Link website with varnish
     *
     * @param int $varnish
     * @param int $website
     *
     * @return bool
     */
    public function link(int $varnish, int $website): bool
    {
        $statement = $this->database->prepare(
            'INSERT INTO varnishes_websites (varnish_id, website_id) VALUES (:varnish_id, :website_id)'
        );
        $statement->bindParam(':varnish_id', $varnish, \PDO::PARAM_INT);
        $statement->bindParam(':website_id', $website, \PDO::PARAM_INT);
        return $statement->execute();
    }

    /**
     * Unlink website with varnish
     *
     * @param int $varnish
     * @param int $website
     *
     * @return bool
     */
    public function unlink(int $varnish, int $website): bool
    {
        $statement = $this->database->prepare(
            'DELETE FROM varnishes_websites WHERE varnish_id = :varnish_id AND website_id = :website_id'
        );
        $statement->bindParam(':varnish_id', $varnish, \PDO::PARAM_INT);
        $statement->bindParam(':website_id', $website, \PDO::PARAM_INT);
        return $statement->execute();
    }

}