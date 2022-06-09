<?php
namespace Supplier;

class Suppliers {

    private $pdo;

    /**
     * @param \PDo $pdo
     */
    public function __construct(\PDo $pdo){
        $this->pdo = $pdo;
    }

    /**
     * Return suppliers list
     * @return array
     */
    public function getSuppliers(): array{
        return $this->pdo->query("SELECT * FROM `supplier` ORDER BY `name` ASC")->fetchAll();
    }

    /**
     * Return supplier
     * @param int $id
     * @return array
     */
    public function find (int $id): \Supplier\Supplier {
        $statement = $this->pdo->query("SELECT * FROM `supplier` WHERE `id` = $id LIMIT 1");
        $statement->setFetchMode(\PDO::FETCH_CLASS, \Supplier\Supplier::class);
        $result = $statement->fetch();
        if ($result === false) {
            throw new \Exception('Aucun résultat n\'a été trouvé');
        }
        return $result;
    }

    /**
     * Modify datas before insertion in database (creation or update)
     * @param Supplier $supllier
     * @param array $datas
     * @return Supplier
     */
    public function hydrate(Supplier $supllier, array $datas){
        $supllier->setName($datas['name']);
        $supllier->setComment($datas['comment']);
        return $supllier;
    }

    /**
     * Insert new supplier in database
     * @param \Supplier $supllier
     * @return bool
     */
    public function create(\Supplier\Supplier $supllier): bool{
        $statement = $this->pdo->prepare('INSERT INTO `supplier` (`name`, `comment`) VALUES (?,?)');
        return $statement->execute([
            $supllier->getName(),
            $supllier->getComment()
        ]);
    }

    /**
     * Update supplier in database
     * @param Supplier $supllier
     * @return bool
     */
    public function update(\Supplier\Supplier $supllier): bool{
        $statement = $this->pdo->prepare('UPDATE `supplier` SET `name` = ?, `comment` = ? WHERE `id` = ?');
        return $statement->execute([
            $supllier->getName(),
            $supllier->getComment(),
            $supllier->getId()
        ]);
    }

    /**
     *  Delete supplier in database
     * @param Supplier $supllier
     * @return bool
     */
    public function delete(\Supplier\Supplier $supllier): bool{
        $statement = $this->pdo->prepare('DELETE from `supplier` WHERE `id` = ?');
        return $statement->execute([
            $supllier->getId()
        ]);
    }
}