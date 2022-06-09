<?php
namespace Lang;

class Langs {

    private $pdo;

    /**
     * @param \PDo $pdo
     */
    public function __construct(\PDo $pdo){
        $this->pdo = $pdo;
    }

    /**
     * Return langs list
     * @return array
     */
    public function getLangs(): array{
        return $this->pdo->query("SELECT * FROM `lang`")->fetchAll();
    }

    /**
     * Return lang
     * @param int $id
     * @return array
     */
    public function find (int $id): \Lang\Lang {
        $statement = $this->pdo->query("SELECT * FROM `lang` WHERE `id` = $id LIMIT 1");
        $statement->setFetchMode(\PDO::FETCH_CLASS, \Lang\Lang::class);
        $result = $statement->fetch();
        if ($result === false) {
            throw new \Exception('Aucun résultat n\'a été trouvé');
        }
        return $result;
    }

    /**
     * Modify datas before insertion in database (creation or update)
     * @param Lang $lang
     * @param array $datas
     * @return Lang
     */
    public function hydrate(Lang $lang, array $datas){
        $lang->setName($datas['name']);
        $lang->setCode($datas['code']);
        return $lang;
    }

    /**
     * Insert new lang in database
     * @param \Lang $lang
     * @return bool
     */
    public function create(\Lang\Lang $lang): bool{
        $statement = $this->pdo->prepare('INSERT INTO `lang` (`name`, `code`) VALUES (?,?)');
        return $statement->execute([
            $lang->getName(),
            $lang->getCode()
        ]);
    }

    /**
     * Update lang in database
     * @param Lang $lang
     * @return bool
     */
    public function update(\Lang\Lang $lang): bool{
        $statement = $this->pdo->prepare('UPDATE `lang` SET `name` = ?, `code` = ? WHERE `id` = ?');
        return $statement->execute([
            $lang->getName(),
            $lang->getCode(),
            $lang->getId()
        ]);
    }

    /**
     *  Delete lang in database
     * @param Lang $lang
     * @return bool
     */
    public function delete(\Lang\Lang $lang): bool{
        $statement = $this->pdo->prepare('DELETE from `lang` WHERE `id` = ?');
        return $statement->execute([
            $lang->getId()
        ]);
    }
}