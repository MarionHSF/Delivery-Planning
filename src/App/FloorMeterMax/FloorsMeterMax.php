<?php
namespace FloorMeterMax;

class FloorsMeterMax {

    private $pdo;

    /**
     * @param \PDo $pdo
     */
    public function __construct(\PDo $pdo){
        $this->pdo = $pdo;
    }

    /**
     * Return floor meter max
     * @param int $id
     * @return array
     */
    public function find (): \FloorMeterMax\FloorMeterMax {
        $statement = $this->pdo->query("SELECT * FROM `floor_meter_max` LIMIT 1");
        $statement->setFetchMode(\PDO::FETCH_CLASS, \FloorMeterMax\FloorMeterMax::class);
        $result = $statement->fetch();
        if ($result === false) {
            throw new \Exception('Aucun résultat n\'a été trouvé');
        }
        return $result;
    }

    /**
     * Modify datas before insertion in database (creation or update)
     * @param FloorMeterMax $floorsMeterMax
     * @param array $datas
     * @return FloorMeterMax
     */
    public function hydrate(FloorMeterMax $floorsMeterMax, array $datas){
        $floorsMeterMax->setFloorMeter($datas['floor_meter_max']);
        return $floorsMeterMax;
    }

    /**
     * Update max floor meter in database
     * @param FloorMeterMax $floorsMeterMax
     * @return bool
     */
    public function update(\FloorMeterMax\FloorMeterMax $floorsMeterMax): void{
        try{
            $this->pdo->beginTransaction();
            $statement = $this->pdo->prepare('UPDATE `floor_meter_max` SET `floor_meter` = ? WHERE `id` = 1');
            $statement->execute([
                $floorsMeterMax->getFloorMeter()
            ]);
            $this->pdo->commit();
        }catch(\PDOException){
            header('Location: /views/floorMeter/edit.php?id=1&errorDB=1');
            exit();
        }
    }
}