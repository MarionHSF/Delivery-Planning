<?php

namespace PDO;

class PDO
{

    public function get_pdo(): \PDO{
        return new \PDO('mysql:host='.getenv()['ARTIFAKT_MYSQL_DATABASE_NAME'].'_mysql_1:3306;dbname='.getenv()['ARTIFAKT_MYSQL_DATABASE_NAME'],getenv()['ARTIFAKT_MYSQL_USER'], getenv()['ARTIFAKT_MYSQL_PASSWORD'], [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
        ]);
    }

}
