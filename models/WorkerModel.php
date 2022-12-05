<?php
class WorkerModel {
    public static function getAll() {
        $sql = 'SELECT `worker`.`id`, `worker`.`workshop_id`, `worker`.`post_id`, `worker`.`fullname`, `post`.`title` as post, `workshop`.title as workshop
                FROM `worker` 
                LEFT JOIN `post` 
                ON `post`.`id` = `worker`.`post_id` 
                LEFT JOIN `workshop` 
                ON `workshop`.`id` = `worker`.`workshop_id`
                WHERE `worker`.`is_deleted` = 0';
        return DatabaseHandler::GetAll($sql);
    }

    public static function getOne($id) {
        $sql = 'SELECT `worker`.`id`, `worker`.`workshop_id`, `worker`.`post_id`, `worker`.`fullname`, `post`.`title` as post, `post`.`discount_percent` as discount, `workshop`.title as workshop
                FROM `worker` 
                LEFT JOIN `post` 
                ON `post`.`id` = `worker`.`post_id` 
                LEFT JOIN `workshop` 
                ON `workshop`.`id` = `worker`.`workshop_id`
                WHERE `worker`.`id` = :workerId AND `worker`.`is_deleted` = 0';
        $params = array (':workerId' => $id);
        return DatabaseHandler::GetAll($sql, $params);
    }

    public static function getWorkersReceivingByMonth($month) {
        $sql = 'SELECT `worker`.`id`, `worker`.`workshop_id`, `worker`.`post_id`, `worker`.`fullname`, `post`.`title` as post, `workshop`.title as workshop
                FROM `worker` 
                LEFT JOIN `receiving`
                ON `worker`.`id` = `receiving`.`worker_id`
                LEFT JOIN `post`
                ON `post`.`id` = `worker`.`post_id`
                LEFT JOIN `workshop` 
                ON `workshop`.`id` = `worker`.`workshop_id`
                WHERE MONTH(`receiving`.`receiving_date`) = :month
                AND YEAR(`receiving`.`receiving_date`) = YEAR(NOW())
                AND `worker`.`is_deleted` = 0 
                AND `receiving`.`worker_id` IS NOT NULL';
        $params = array (':month' => $month);
        return DatabaseHandler::GetAll($sql, $params);
    }

    public static function getWorkersWhereDiscountBetween($from, $before) {
        $sql = 'SELECT `worker`.`id`, `worker`.`workshop_id`, `worker`.`post_id`, `worker`.`fullname`, `post`.`title` as post, `workshop`.title as workshop 
                FROM `worker` 
                LEFT JOIN `post`
                ON `worker`.`post_id` = `post`.`id`
                LEFT JOIN `workshop` 
                ON `workshop`.`id` = `worker`.`workshop_id`
                WHERE `post`.`discount_percent` BETWEEN :from AND :before
                AND `worker`.`is_deleted` = 0';
        $params = array (':from' => $from, ':before' => $before);
        return DatabaseHandler::GetAll($sql, $params);
    }

    public static function getWorkersReceiving() {
        $sql = 'SELECT `worker`.`fullname`, `wear`.`type`, `wear`.`price` 
                FROM `worker` 
                LEFT JOIN `receiving`
                ON `receiving`.`worker_id` = `worker`.`id`
                LEFT JOIN `receiving_wear`
                ON `receiving_wear`.`receiving_id` = `receiving`.`id`
                LEFT JOIN `wear`
                ON `wear`.`id` = `receiving_wear`.`wear_id`
                WHERE `worker`.`is_deleted` = 0 AND `wear`.`type` IS NOT NULL AND `wear`.`price` IS NOT NULL';

        return DatabaseHandler::GetAll($sql);
    }

    public static function getWorkersNotReceiving() {
        $sql = 'SELECT `worker`.`id`, `worker`.`workshop_id`, `worker`.`post_id`, `worker`.`fullname`, `post`.`title` as post, `workshop`.title as workshop
                FROM `worker` 
                LEFT JOIN `receiving`
                ON `receiving`.`worker_id` = `worker`.`id`
                LEFT JOIN `post`
                ON `worker`.`post_id` = `post`.`id`
                LEFT JOIN `workshop` 
                ON `workshop`.`id` = `worker`.`workshop_id`
                WHERE `worker`.`is_deleted` = 0 AND `receiving`.`worker_id` IS NULL';
        return DatabaseHandler::GetAll($sql);
    }

    public static function getWorkersWithMaxDiscount() {
        $sql = 'SELECT `worker`.`fullname`, `post`.`title`, `post`.`discount_percent`
                FROM `worker`
                LEFT JOIN `post`
                ON `post`.`id` = `worker`.`post_id`
                WHERE `post`.`discount_percent` = (SELECT MAX(`post`.`discount_percent`) FROM `post`)
                AND `worker`.`is_deleted` = 0';
        return DatabaseHandler::GetAll($sql);
    }

    public static function getWorkersByWorkshop($workshopId) {
        $sql = 'SELECT `worker`.`id`, `worker`.`fullname`, `post`.`title`, `post`.`discount_percent`
                FROM `worker`
                LEFT JOIN `post`
                ON `post`.`id` = `worker`.`post_id`
                LEFT JOIN `workshop`
                ON `workshop`.`id` = `worker`.`workshop_id`
                WHERE `workshop`.`id` = :workshopId AND `worker`.`is_deleted` = 0';
        $params = array (':workshopId' => $workshopId);
        return DatabaseHandler::GetAll($sql, $params);
    }

    public static function getWorkerReceiving($id) {
        $sql = 'SELECT `receiving_wear`.`id`, `worker`.`fullname`, `wear`.`type`, `wear`.`price`, `receiving`.`receiving_date`, `wear`.`wear_time` 
                FROM `worker` 
                LEFT JOIN `receiving`
                ON `receiving`.`worker_id` = `worker`.`id`
                LEFT JOIN `receiving_wear`  
                ON `receiving_wear`.`receiving_id` = `receiving`.`id`
                LEFT JOIN `wear`
                ON `wear`.`id` = `receiving_wear`.`wear_id`
                WHERE `worker`.`is_deleted` = 0 AND `worker`.`id` = :workerId AND `wear`.`price` IS NOT NULL AND `wear`.`type` IS NOT NULL';
        $params = array (':workerId' => $id);
        return DatabaseHandler::GetAll($sql, $params);
    }

    public static function getWorkerBoss($id) {
        $sql = 'SELECT `worker`.`id`, `worker`.`fullname`
                FROM `worker`
                LEFT JOIN `workshop`
                ON `workshop`.`id` = `worker`.`workshop_id`
                LEFT JOIN `post`
                ON `post`.`id` = `worker`.`post_id` 
                WHERE `post`.`title` IN (
                    SELECT `post`.`title`
                    FROM `worker` 
                    LEFT JOIN `post`
                    ON `post`.`id` = `worker`.`post_id`
                    WHERE `post`.`title` LIKE "Директор%" OR `post`.`title` LIKE "Управляющий%"
                ) AND `workshop`.`id` = (
                    SELECT `worker`.`workshop_id`
                    FROM `worker`
                    WHERE `worker`.`id` = :workerId
                ) AND `worker`.`is_deleted` = 0';
        $params = array (':workerId' => $id);
        return DatabaseHandler::GetAll($sql, $params);
    }

    public static function createOne($workshopId, $postId, $fullName) {
        $sql = 'INSERT INTO `worker` (`workshop_id`, `post_id`, `fullname`)
                    VALUES (:workshopId, :postId, :fullName)';
        $params = array (':workshopId' => $workshopId, ':postId' => $postId, ':fullName' => $fullName);
        return DatabaseHandler::InsertRow($sql, $params);
    }

    public static function putOne($id, $workshopId, $postId, $fullName) {
        $sql = 'UPDATE `worker` SET `workshop_id` = :workshopId, `post_id` = :postId, `fullname` = :fullName WHERE `id` = :workerId';
        $params = array (':workerId' => $id, ':workshopId' => $workshopId, ':postId' => $postId, ':fullName' => $fullName);
        DatabaseHandler::Execute($sql, $params);
        return $id;
    }

    public static function deleteOne($id) {
        $sql = 'UPDATE `worker` SET `is_deleted` = 1 WHERE `id` = :workerId';
        $params = array (':workerId' => $id);
        DatabaseHandler::Execute($sql, $params);
        return $id;
    }
}