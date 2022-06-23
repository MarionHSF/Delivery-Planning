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
     *  Delete file in database
     * @param File $file
     * @return bool
     */
    public function delete(\File\File $file): bool{
        $statement = $this->pdo->prepare('DELETE from `event_file` WHERE `id_file` = ?');
        $statement->execute([
            $file->getId()
        ]);

        $statement2 = $this->pdo->prepare('DELETE from `file` WHERE `id` = ?');
        return $statement2->execute([
            $file->getId()
        ]);
    }
}