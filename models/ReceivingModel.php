<?php
class ReceivingModel {
    public static function getAll() {
        $sql = 'SELECT `receiving_wear`.`id` as id, `receiving`.`id` as receiving_id, `wear`.`id` as wear_id, `receiving`.`worker_id`, `worker`.`fullname`, `receiving`.`receiving_date`, `receiving`.`sign`, `wear`.`type`, `wear`.`wear_time`, `wear`.`price`, `post`.`discount_percent`
                FROM `receiving` 
                LEFT JOIN `worker`
                ON `worker`.`id` = `receiving`.`worker_id`
                LEFT JOIN `post`
                ON `worker`.`post_id` = `post`.`id`
                LEFT JOIN `receiving_wear` 
                ON `receiving_wear`.`receiving_id` = `receiving`.`id`
                LEFT JOIN `wear`
                ON `wear`.`id` = `receiving_wear`.`wear_id`
                WHERE `receiving`.`is_deleted` = 0';

        return DatabaseHandler::GetAll($sql);
    }

    public static function getOne($receivingId) {
        $sql = 'SELECT `receiving_wear`.`id` as id, `receiving`.`id` as receiving_id, `wear`.`id` as wear_id, `receiving`.`worker_id`, `worker`.`fullname`, `receiving`.`receiving_date`, `receiving`.`sign`, `wear`.`type`, `wear`.`wear_time`, `wear`.`price`, `post`.`discount_percent`
                FROM `receiving` 
                LEFT JOIN `worker`
                ON `worker`.`id` = `receiving`.`worker_id`
                LEFT JOIN `post`
                ON `worker`.`post_id` = `post`.`id`
                LEFT JOIN `receiving_wear` 
                ON `receiving_wear`.`receiving_id` = `receiving`.`id`
                LEFT JOIN `wear`
                ON `wear`.`id` = `receiving_wear`.`wear_id`
                WHERE `receiving`.`is_deleted` = 0 AND `receiving`.`id` = :receivingId';
        $params = array (':receivingId' => $receivingId);
        return DatabaseHandler::GetAll($sql, $params);
    }

    public static function getReport($month, $year) {
        $sql = 'SELECT `receiving_wear`.`id`, `worker`.`fullname`,  `wear`.`type`, `wear`.`price`, `workshop`.`title` as workshop, `post`.`discount_percent` as discount
                FROM `receiving`
                LEFT JOIN `receiving_wear`
                ON `receiving_wear`.`receiving_id` = `receiving`.`id`
                LEFT JOIN `wear`
                ON `wear`.`id` = `receiving_wear`.`wear_id`
                LEFT JOIN `worker`
                ON `receiving`.`worker_id` = `worker`.`id`
                LEFT JOIN `workshop`
                ON `workshop`.`id` = `worker`.`workshop_id`
                LEFT JOIN `post`
                ON `post`.`id` = `worker`.`post_id`
                WHERE MONTH(`receiving`.`receiving_date`) = :month AND YEAR(`receiving`.`receiving_date`) = :year';
        $params = array (':month' => $month, ':year' => $year);
        return DatabaseHandler::GetAll($sql, $params);
    }

    public static function createOne($workerId, $receivingDate, $sign, $wears) {
        $sql = 'INSERT INTO `receiving` (`worker_id`, `receiving_date`, `sign`)
                    VALUES (:workerId, :receivingDate, :sign)';
        $params = array (':workerId' => $workerId, ':receivingDate' => $receivingDate, ':sign' => $sign);
        $receivingId = DatabaseHandler::InsertRow($sql, $params);

        foreach ($wears as $wear) {
            $receivingWearSql = 'INSERT INTO `receiving_wear` (`wear_id`, `receiving_id`) 
                                        VALUES (:wearId, :receivingId)';
            $receivingWearParams = array(':wearId' => $wear, ':receivingId' => $receivingId);
            DatabaseHandler::InsertRow($receivingWearSql, $receivingWearParams);
        }

        return $receivingId;
    }

    public static function putOne($receivingId, $workerId, $receivingDate, $sign, $wears) {
        $sql = 'UPDATE `receiving` SET 
                       `worker_id` = :workerId,
                       `receiving_date` = :receivingDate, 
                       `sign` = :sign
                WHERE `id` = :receivingId';
        $params = array (':receivingId' => $receivingId, ':workerId' => $workerId, ':receivingDate' => $receivingDate, ':sign' => $sign);
        DatabaseHandler::Execute($sql, $params);

        $deleteSql = 'DELETE FROM `receiving_wear` WHERE `receiving_id` = :receivingId';
        $deleteParams = array(':receivingId' => $receivingId);
        DatabaseHandler::Execute($deleteSql, $deleteParams);

        foreach ($wears as $wear) {
            $receivingWearSql = 'INSERT INTO `receiving_wear` (`wear_id`, `receiving_id`) 
                                        VALUES (:wearId, :receivingId)';
            $receivingWearParams = array(':wearId' => $wear, ':receivingId' => $receivingId);
            DatabaseHandler::InsertRow($receivingWearSql, $receivingWearParams);
        }

        return $receivingId;
    }

    public static function deleteOne($receivingId) {
        $sql = 'UPDATE `receiving` SET `is_deleted` = 1 WHERE `id` = :receivingId';
        $params = array (':receivingId' => $receivingId);
        DatabaseHandler::Execute($sql, $params);
        return $receivingId;
    }
}