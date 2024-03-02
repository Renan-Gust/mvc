<?php

namespace core;

use \core\Database;
use \ClanCats\Hydrahon\Builder;
use \ClanCats\Hydrahon\Query\Sql\FetchableInterface;
use \Doctrine\Inflector\InflectorFactory;
use ClanCats\Hydrahon\Query\Sql\Insert;
use \Doctrine\Inflector\Rules\Pattern;
use \Doctrine\Inflector\Rules\Patterns;
use \Doctrine\Inflector\Rules\Ruleset;
use \Doctrine\Inflector\Rules\Substitution;
use \Doctrine\Inflector\Rules\Substitutions;
use \Doctrine\Inflector\Rules\Transformation;
use \Doctrine\Inflector\Rules\Transformations;
use \Doctrine\Inflector\Rules\Word;

class Model
{

    protected static $_h;
    protected static $_tableName;

    public function __construct()
    {
        self::_checkH();
    }

    public static function _checkH()
    {
        if (self::$_h == null) {
            $connection = Database::getInstance();
            self::$_h = new Builder('mysql', function ($query, $queryString, $queryParameters) use ($connection) {
                $statement = $connection->prepare($queryString);
                $statement->execute($queryParameters);

                if ($query instanceof FetchableInterface) {
                    return $statement->fetchAll(\PDO::FETCH_ASSOC);
                }
            });
        }

        self::$_h = self::$_h->table(self::getTableName());
        self::$_tableName = self::getTableName();
    }

    public static function getTableName()
    {
        $inflector = InflectorFactory::create()->build();

        $className = explode('\\', get_called_class());
        $className = end($className);

        $tableName = $inflector->tableize($className);
        $finalTableName = $inflector->pluralize($tableName);

        if ($finalTableName === $tableName && substr($finalTableName, -1) !== "s") {
            $inflector = InflectorFactory::create()
                ->withPluralRules(
                    new Ruleset(
                        new Transformations(
                            new Transformation(new Pattern('^(bil)er$'), '\1'),
                            new Transformation(new Pattern('^(inflec|contribu)tors$'), '\1ta')
                        ),
                        new Patterns(new Pattern('noflect'), new Pattern('abtuse')),
                        new Substitutions(
                            new Substitution(new Word($finalTableName), new Word($finalTableName . "s")),
                        )
                    )
                )
                ->build();

            $finalTableName = $inflector->pluralize($finalTableName);
        }

        return strtolower($finalTableName);
    }

    public static function select($fields = [])
    {
        self::_checkH();
        return self::$_h->select($fields);
    }

    public static function insert($fields = [])
    {
        self::_checkH();
        // return self::$_h->insert($fields);

        return self::getLastInsertId()->table(self::$_tableName)->insert($fields);
    }

    public static function update($fields = [])
    {
        self::_checkH();
        return self::$_h->update($fields);
    }

    public static function delete()
    {
        self::_checkH();
        return self::$_h->delete();
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
