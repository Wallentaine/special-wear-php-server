<?php
class WearModel {
    public static function getAll() {
        $sql = 'SELECT `id`, `type`, `wear_time`, `price` FROM `wear` WHERE `is_deleted` = 0';
        return DatabaseHandler::GetAll($sql);
    }

    public static function getOne($id) {
        $sql = 'SELECT `id`, `type`, `wear_time`, `price` FROM `wear` WHERE `id` = :wearId AND `is_deleted` = 0';
        $params = array (':wearId' => $id);
        return DatabaseHandler::GetAll($sql, $params);
    }

    public static function createOne($type, $wearTime, $price) {
        $sql = 'INSERT INTO `wear` (`type`, `wear_time`, `price`)
                    VALUES (:type, :wearTime, :price)';
        $params = array (':type' => $type, ':wearTime' => $wearTime, ':price' => $price);
        return DatabaseHandler::InsertRow($sql, $params);
    }

    public static function putOne($id, $type, $wearTime, $price) {
        $sql = 'UPDATE `wear` SET `type` = :type, `wear_time` = :wearTime, `price` = :price, `is_deleted` = 0 WHERE `id` = :wearId';
        $params = array (':wearId' => $id, ':type' => $type, ':wearTime' => $wearTime, ':price' => $price);
        DatabaseHandler::Execute($sql, $params);
        return $id;
    }

    public static function deleteOne($id) {
        $sql = 'UPDATE `wear` SET `is_deleted` = 1 WHERE `id` = :wearId';
        $params = array (':wearId' => $id);
        DatabaseHandler::Execute($sql, $params);
        return $id;
    }
}