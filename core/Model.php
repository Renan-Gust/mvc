<?php
namespace core;

use \core\Database;
use \ClanCats\Hydrahon\Builder;
use \ClanCats\Hydrahon\Query\Sql\FetchableInterface;
use \Doctrine\Inflector\InflectorFactory;
use ClanCats\Hydrahon\Query\Sql\Insert;

class Model {

    protected static $_h;
    protected static $_tableName;
    
    public function __construct() {
        self::_checkH();
    }

    public static function _checkH() {
        if(self::$_h == null) {
            $connection = Database::getInstance();
            self::$_h = new Builder('mysql', function($query, $queryString, $queryParameters) use($connection) {
                $statement = $connection->prepare($queryString);
                $statement->execute($queryParameters);

                if ($query instanceof FetchableInterface)
                {
                    return $statement->fetchAll(\PDO::FETCH_ASSOC);
                }
            });
        }
        
        self::$_h = self::$_h->table( self::getTableName() );
        self::$_tableName = self::getTableName();
    }

    public static function getTableName() {
        $inflector = InflectorFactory::create()->build();
        
        $className = explode('\\', get_called_class());
        $className = end($className);

        $tableName = $inflector->tableize($className);
        $finalTableName = $inflector->pluralize($tableName);
        return strtolower($finalTableName);
    }

    public static function select($fields = []) {
        self::_checkH();
        return self::$_h->select($fields)->execute();
    }

    public static function insert($fields = []) {
        self::_checkH();
        // return self::$_h->insert($fields);

        return self::getLastInsertId()->table(self::$_tableName)->insert($fields)->execute();
    }

    public static function update($fields = []) {
        self::_checkH();
        return self::$_h->update($fields)->execute();
    }

    public static function delete() {
        self::_checkH();
        return self::$_h->delete()->execute();
    }

    public static function getLastInsertId()
    {
        $connection = Database::getInstance();

        $builder = new Builder('mysql', function ($query, $queryString, $queryParameters) use ($connection) {
            $statement = $connection->prepare($queryString);
            $statement->execute($queryParameters);

            if ($query instanceof Insert) {
                return $connection->lastInsertId();
            }
        });

        return $builder;
    }
}