<?php
class PostModel {
    public static function getAll() {
        $sql = 'SELECT `id`, `title`, `discount_percent` FROM `post` WHERE `is_deleted` = 0';
        return DatabaseHandler::GetAll($sql);
    }

    public static function getOne($postId) {
        $sql = 'SELECT `id`, `title`, `discount_percent` FROM `post` WHERE `id` = :postId AND `is_deleted` = 0';
        $params = array (':postId' => $postId);
        return DatabaseHandler::GetAll($sql, $params);
    }

    public static function createOne($title, $discountPercent) {
        $sql = 'INSERT INTO `post` (`title`, `discount_percent`)
                    VALUES (:title, :discountPercent)';
        $params = array (':title' => $title, ':discountPercent' => $discountPercent);
        return DatabaseHandler::InsertRow($sql, $params);
    }

    public static function putOne($postId, $title, $discountPercent) {
        $sql = 'UPDATE `post` SET `title` = :title, `discount_percent` = :discountPercent WHERE `id` = :postId';
        $params = array (':postId' => $postId, ':title' => $title, ':discountPercent' => $discountPercent);
        DatabaseHandler::Execute($sql, $params);
        return $postId;
    }

    public static function deleteOne($postId) {
        $sql = 'UPDATE `post` SET `is_deleted` = 1 WHERE `id` = :postId';
        $params = array (':postId' => $postId);
        DatabaseHandler::Execute($sql, $params);
        return $postId;
    }
}