<?php
class WorkshopModel {
    public static function getAll() {
        $sql = 'SELECT `id`, `title` FROM `workshop` WHERE `is_deleted` = 0';
        return DatabaseHandler::GetAll($sql);
    }

    public static function getOne($id) {
        $sql = 'SELECT `id`, `title` FROM `workshop` WHERE `id` = :workshopId AND `is_deleted` = 0';
        $params = array (':workshopId' => $id);
        return DatabaseHandler::GetAll($sql, $params);
    }

    public static function createOne($title) {
        $sql = 'INSERT INTO `workshop` (`title`) VALUES (:title)';
        $params = array (':title' => $title);
        return DatabaseHandler::InsertRow($sql, $params);
    }

    public static function putOne($id, $title) {
        $sql = 'UPDATE `workshop` SET `title` = :title, `is_deleted` = 0 WHERE `id` = :workshopId';
        $params = array (':workshopId' => $id, ':title' => $title);
        DatabaseHandler::Execute($sql, $params);
        return $id;
    }

    public static function deleteOne($id) {
        $sql = 'UPDATE `workshop` SET `is_deleted` = 1 WHERE `id` = :workshopId';
        $params = array (':workshopId' => $id);
        DatabaseHandler::Execute($sql, $params);
        return $id;
    }
}