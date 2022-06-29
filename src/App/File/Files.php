<?php
namespace File;

class Files {

    private $pdo;

    /**
     * @param \PDo $pdo
     */
    public function __construct(\PDo $pdo){
        $this->pdo = $pdo;
    }

    /**
     * Return upload files list
     * @return array
     */
    public function getUploadFiles(): array{
        return $this->pdo->query("SELECT * FROM `file`")->fetchAll();
    }

    /**
     * Return file
     * @param int $id
     * @return array
     */
    public function find (int $id): \File\File {
        $statement = $this->pdo->query("SELECT * FROM `file` WHERE `id` = $id LIMIT 1");
        $statement->setFetchMode(\PDO::FETCH_CLASS, \File\File::class);
        $result = $statement->fetch();
        if ($result === false) {
            throw new \Exception('Aucun résultat n\'a été trouvé');
        }
        return $result;
    }

    /**
     * Return id event of file
     * @param int $id
     * @return array
     * @throws \Exception
     */
    public function findEvent (int $id): array {
        $statement = $this->pdo->query("SELECT * FROM `event` LEFT JOIN (`event_file`, `file`) ON (`event`.`id` = `event_file`.`id_event` AND `event_file`.`id_file` = `file`.`id`) WHERE `file`.`id` = $id");
        $result = $statement->fetchAll();
        if ($result === false) {
            throw new \Exception('Aucun résultat n\'a été trouvé');
        }
        return $result;
    }

    /**
     * Modify datas before insertion in database (creation or update)
     * @param File $file
     * @param array $data
     * @return File
     */
    public function hydrate(File $file, string $data){
        $file->setName($data);
        return $file;
    }

    /**
     * Insert new file in database
     * @param \File $file
     * @return bool
     */
    public function create(\File\File $file): bool{
        $statement = $this->pdo->prepare('INSERT INTO `file` (`name`) VALUES (?)');
        return $statement->execute([
            $file->getName(),
        ]);
    }

    /**
     *  Call delete file function
     * @param File $file
     * @return bool
     */
    public function delete(\File\File $file): void{
        $id_file = $file->getId();
        if(strpos($_SERVER['HTTP_REFERER'], 'views/event/event')){
            $this->deleteFile($id_file);
        }else{
            try{
                $this->pdo->beginTransaction();
                $this->deleteFile($id_file);
                $this->pdo->commit();
            }catch(\PDOException $e){
                echo 'errorDB';
            }
        }
    }

    /**
     * Delete file in database
     * @param $id
     * @return void
     */
    public function deleteFile($id){
        $statement = $this->pdo->prepare('DELETE from `event_file` WHERE `id_file` = ?');
        $statement->execute([
            $id
        ]);

        $statement2 = $this->pdo->prepare('DELETE from `file` WHERE `id` = ?');
        $statement2->execute([
            $id
        ]);
    }
}