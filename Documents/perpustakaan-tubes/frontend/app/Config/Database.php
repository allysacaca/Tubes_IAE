<?php

namespace Config;

use CodeIgniter\Database\Config;

class Database extends Config
{
    public string $defaultGroup = 'default';

    public array $default = [
        'DSN'          => '',
        'hostname'     => 'localhost',
        'username'     => 'root',     // Ganti dengan username database Anda
        'password'     => '',         // Ganti dengan password database Anda
        'database'     => 'library_management', // Nama database
        'DBDriver'     => 'MySQLi',
        'DBPrefix'     => '',
        'pConnect'     => false,
        'DBDebug'      => true,
        'charset'      => 'utf8',
        'DBCollat'     => 'utf8_general_ci',
        'swapPre'      => '',
        'encrypt'      => false,
        'compress'     => false,
        'strictOn'     => false,
        'failover'     => [],
        'port'         => 3306,
        'numberNative' => false,
        'dateFormat'   => [
            'date'     => 'Y-m-d',
            'datetime' => 'Y-m-d H:i:s',
            'time'     => 'H:i:s',
        ],
    ];
}