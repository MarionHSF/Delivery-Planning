<?php

namespace PDO;

class PDO
{

    public function get_pdo(): \PDO{
        return new \PDO('mysql:host=agenda_mysql_1:3306;dbname=agenda','agenda', 'agenda', [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
        ]);
    }

}
