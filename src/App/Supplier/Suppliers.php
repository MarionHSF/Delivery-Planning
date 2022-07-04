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
     * @param Supplier $supplier
     * @param array $datas
     * @return Supplier
     */
    public function hydrate(Supplier $supplier, array $datas){
        $supplier->setName($datas['name']);
        isset($datas['reserved_14h']) ? $supplier->setReserved14h('yes') : $supplier->setReserved14h('no') ;
        $supplier->setComment($datas['comment']);
        return $supplier;
    }

    /**
     * Insert new supplier in database
     * @param \Supplier $supplier
     * @return bool
     */
    public function create(\Supplier\Supplier $supplier): void{
        try{
            $this->pdo->beginTransaction();
            $statement = $this->pdo->prepare('INSERT INTO `supplier` (`name`, `reserved_14h`, `comment`) VALUES (?,?,?)');
            $statement->execute([
                $supplier->getName(),
                $supplier->getReserver14h(),
                $supplier->getComment()
            ]);
            $this->pdo->commit();
        }catch(\PDOException $e){
            dd($e);
            die();
            header('Location: /views/supplier/add.php?errorDB=1');
            exit();
        }
    }

    /**
     * Update supplier in database
     * @param Supplier $supplier
     * @return bool
     */
    public function update(\Supplier\Supplier $supplier): void{
        try{
            $this->pdo->beginTransaction();
            $statement = $this->pdo->prepare('UPDATE `supplier` SET `name` = ?, `reserved_14h` = ?, `comment` = ? WHERE `id` = ?');
            $statement->execute([
                $supplier->getName(),
                $supplier->getReserver14h(),
                $supplier->getComment(),
                $supplier->getId()
            ]);
            $this->pdo->commit();
        }catch(\PDOException){
            header('Location: /views/supplier/edit.php?id='.$supplier->getId().'&errorDB=1');
            exit();
        }
    }

    /**
     *  Delete supplier in database
     * @param Supplier $supplier
     * @return bool
     */
    public function delete(\Supplier\Supplier $supplier): void{
        try{
            $this->pdo->beginTransaction();
            $statement = $this->pdo->prepare('DELETE from `supplier` WHERE `id` = ?');
            $statement->execute([
                $supplier->getId()
            ]);
            $this->pdo->commit();
        }catch(\PDOException){
            header('Location: /views/supplier/supplier.php?id='.$supplier->getId().'&errorDB=1');
            exit();
        }
    }
}