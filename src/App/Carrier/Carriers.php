<?php
namespace Carrier;

class Carriers {

    private $pdo;

    /**
     * @param \PDo $pdo
     */
    public function __construct(\PDo $pdo){
        $this->pdo = $pdo;
    }

    /**
     * Return carriers list
     * @return array
     */
    public function getCarriers(): array{
        return $this->pdo->query("SELECT * FROM `carrier` ORDER BY `name` ASC")->fetchAll();
    }

    /**
     * Return carrier
     * @param int $id
     * @return array
     */
    public function find (int $id): \Carrier\Carrier {
        $statement = $this->pdo->query("SELECT * FROM `carrier` WHERE `id` = $id LIMIT 1");
        $statement->setFetchMode(\PDO::FETCH_CLASS, \Carrier\Carrier::class);
        $result = $statement->fetch();
        if ($result === false) {
            throw new \Exception('Aucun résultat n\'a été trouvé');
        }
        return $result;
    }

    /**
     * Modify datas before insertion in database (creation or update)
     * @param Carrier $carrier
     * @param array $datas
     * @return Carrier
     */
    public function hydrate(Carrier $carrier, array $datas){
        $carrier->setName($datas['name']);
        $carrier->setComment($datas['comment']);
        return $carrier;
    }

    /**
     * Insert new carrier in database
     * @param \Carrier $carrier
     * @return bool
     */
    public function create(\Carrier\Carrier $carrier): void{
        try{
            $this->pdo->beginTransaction();
            $statement = $this->pdo->prepare('INSERT INTO `carrier` (`name`, `comment`) VALUES (?,?)');
            $statement->execute([
                $carrier->getName(),
                $carrier->getComment()
            ]);
            $this->pdo->commit();
        }catch(\PDOException){
            header('Location: /views/carrier/add.php?errorDB=1');
            exit();
        }
    }

    /**
     * Update carrier in database
     * @param Carrier $carrier
     * @return bool
     */
    public function update(\Carrier\Carrier $carrier): void{
        try{
            $this->pdo->beginTransaction();
            $statement = $this->pdo->prepare('UPDATE `carrier` SET `name` = ?, `comment` = ? WHERE `id` = ?');
            $statement->execute([
                $carrier->getName(),
                $carrier->getComment(),
                $carrier->getId()
            ]);
            $this->pdo->commit();
        }catch(\PDOException){
            header('Location: /views/carrier/edit.php?id='.$carrier->getId().'&errorDB=1');
            exit();
        }
    }

    /**
     *  Delete carrier in database
     * @param Carrier $carrier
     * @return bool
     */
    public function delete(\Carrier\Carrier $carrier): void{
        try{
            $this->pdo->beginTransaction();
            $statement = $this->pdo->prepare('DELETE from `carrier` WHERE `id` = ?');
            $statement->execute([
                $carrier->getId()
            ]);
            $this->pdo->commit();
        }catch(\PDOException){
            header('Location: /views/carrier/carrier.php?id='.$carrier->getId().'&errorDB=1');
            exit();
        }
    }
}