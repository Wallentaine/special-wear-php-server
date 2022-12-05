<?php
class UserModel {

    public static function existUser($email) {
        $sql = 'SELECT `id`, `email`, `role`, `password` FROM `user` WHERE `email` = :email';
        $params = array (':email' => $email);
        return DatabaseHandler::GetRow($sql, $params);
    }

    public static function registration($email, $pass, $role) {
        $sql = 'INSERT INTO `user` (`email`, `password`, `role`)
                    VALUES (:email, :pass, :role)';
        $params = array (':email' => $email, ':pass' => $pass, ':role' => $role);
        return DatabaseHandler::InsertRow($sql, $params);
    }
}