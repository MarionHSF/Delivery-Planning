<?php
namespace User;

class Users {

    private $pdo;

    /**
     * @param \PDo $pdo
     */
    public function __construct(\PDo $pdo){
        $this->pdo = $pdo;
    }

    /**
     * Return customers users list
     * @return array
     */
    public function getCustomersUsers(): array{
        return $this->pdo->query("SELECT * FROM `user` WHERE `id_role` = 1 ORDER BY `company_name` ASC")->fetchAll();
    }

    /**
     * Return admin users list
     * @return array
     */
    public function getAdminUsers(): array{
        return $this->pdo->query("SELECT * FROM `user` WHERE `id_role` != 1 ORDER BY `name` ASC")->fetchAll();
    }

    /**
     * Return user
     * @param int $id
     * @return array
     */
    public function find (int $id): \User\User {
        $statement = $this->pdo->query("SELECT * FROM `user` WHERE `id` = $id LIMIT 1");
        $statement->setFetchMode(\PDO::FETCH_CLASS, \User\User::class);
        $result = $statement->fetch();
        if ($result === false) {
            throw new \Exception('Aucun résultat n\'a été trouvé');
        }
        return $result;
    }

    /**
     * Return all users email
     * @return array
     */
    public function getUsersEmail (): array{
        return $this->pdo->query("SELECT `email` FROM `user`")->fetchAll();
    }

    /**
     * Modify datas before insertion in database (creation or update)
     * @param User $user
     * @param array $datas
     * @return User
     */
    public function hydrate(User $user, array $datas){
        $user->setCompanyName($datas['company_name']);
        $user->setName($datas['name']);
        $user->setFirstname($datas['firstname']);
        $user->setPhone($datas['phone']);
        $user->setEmail($datas['email']);
        $user->setPassword($datas['password']); //TODO hash
        $user->setIdLang($datas['id_lang']);
        $user->setIdRole($datas['id_role']);
        return $user;
    }

    /**
     * Insert new user in database
     * @param \User $user
     * @return bool
     */
    public function create(\User\User $user): bool{
        $statement = $this->pdo->prepare('INSERT INTO `user` (`company_name`, `name`, `firstname`, `phone`, `email`, `password`, `id_lang`, `id_role`) VALUES (?,?,?,?,?,?,?,?)');
        return $statement->execute([
            $user->getCompanyName(),
            $user->getName(),
            $user->getFirstname(),
            $user->getPhone(),
            $user->getEmail(),
            $user->getPassword(),
            $user->getIdLang(),
            $user->getIdRole()
        ]);
    }

    /**
     * Update user in database
     * @param User $user
     * @return bool
     */
    public function update(\User\User $user): bool{
        $statement = $this->pdo->prepare('UPDATE `user` SET `company_name` = ?, `name` = ?, `firstname` = ?, `phone` = ?, `email` = ?, `password` = ?, `id_lang` = ?, `id_role` = ? WHERE `id` = ?');
        return $statement->execute([
            $user->getCompanyName(),
            $user->getName(),
            $user->getFirstname(),
            $user->getPhone(),
            $user->getEmail(),
            $user->getPassword(),
            $user->getIdLang(),
            $user->getIdRole(),
            $user->getId()
        ]);
    }

    /**
     *  Delete user in database
     * @param User $user
     * @return bool
     */
    public function delete(\User\User $user): bool{
        $statement = $this->pdo->prepare('DELETE from `user` WHERE `id` = ?');
        return $statement->execute([
            $user->getId()
        ]);
    }
}