<?php

namespace Levelup\Framework\Persistence\DB;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;

class ConnectionProvider
{
    private static $dbConfig;
    private static $doctrineConfig;
    private static $connection = null;

    public static function setConfig(array $dbConfig = [], Configuration $doctrineConfig = null)
    {
        self::$dbConfig = $dbConfig;
        if ($doctrineConfig instanceof Configuration) {
            self::$doctrineConfig = $doctrineConfig;

        } else {
            self::$doctrineConfig = new Configuration();
        }
    }

    public static function getConnection()
    {
        if (self::$connection == null) {
            self::$connection = DriverManager::getConnection(self::$dbConfig, self::$doctrineConfig);
        }

        return self::$connection;
    }
}
