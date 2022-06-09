<?php
namespace Role;

class Roles {

    private $pdo;

    /**
     * @param \PDo $pdo
     */
    public function __construct(\PDo $pdo){
        $this->pdo = $pdo;
    }

    /**
     * Return roles list
     * @return array
     */
    public function getRoles(): array{
        return $this->pdo->query("SELECT * FROM `role`")->fetchAll();
    }

    /**
     * Return role
     * @param int $id
     * @return array
     */
    public function find (int $id): \Role\Role {
        $statement = $this->pdo->query("SELECT * FROM `role` WHERE `id` = $id LIMIT 1");
        $statement->setFetchMode(\PDO::FETCH_CLASS, \Role\Role::class);
        $result = $statement->fetch();
        if ($result === false) {
            throw new \Exception('Aucun résultat n\'a été trouvé');
        }
        return $result;
    }

    /**
     * Modify datas before insertion in database (creation or update)
     * @param Role $role
     * @param array $datas
     * @return Role
     */
    public function hydrate(Role $role, array $datas){
        $role->setName($datas['name']);
        return $role;
    }

    /**
     * Insert new role in database
     * @param \Role $role
     * @return bool
     */
    public function create(\Role\Role $role): bool{
        $statement = $this->pdo->prepare('INSERT INTO `role` (`name`) VALUES (?)');
        return $statement->execute([
            $role->getName()
        ]);
    }

    /**
     * Update role in database
     * @param Role $role
     * @return bool
     */
    public function update(\Role\Role $role): bool{
        $statement = $this->pdo->prepare('UPDATE `role` SET `name` = ? WHERE `id` = ?');
        return $statement->execute([
            $role->getName(),
            $role->getId()
        ]);
    }

    /**
     *  Delete role in database
     * @param Role $role
     * @return bool
     */
    public function delete(\Role\Role $role): bool{
        $statement = $this->pdo->prepare('DELETE from `role` WHERE `id` = ?');
        return $statement->execute([
            $role->getId()
        ]);
    }
}