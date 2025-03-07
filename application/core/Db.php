<?php
require 'application/core/builder/Mysql.php';

class Db
{

    private static mysqli $db;

    /**
     * 构造方法
     * @access public
     */
    public static function init(): void
    {
        if (empty($db)){
            $mysql = new Mysql();
            self::$db = $mysql->getMysqlDb();
        }
    }

    /**
     * 数据查询
     * @throws Exception
     */
    public static function selectAll($sql): array
    {
        try {
            self::init();
            $result = self::$db->query($sql);
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * 数据查询
     * @param $sql
     * @return array|false|null
     */
    public static function selectOne($sql): bool|array|null
    {
        self::init();
        $result = self::$db->query($sql);
        return $result->fetch_array(MYSQLI_ASSOC);
    }
}