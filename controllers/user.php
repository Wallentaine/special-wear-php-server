<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require('models/UserModel.php');

const SECRET_KEY = 'vis31_ISIS_labs_key_AKolesnichenko';

function jwtEncodeCustom($id ,$email, $role): string {
    $token = [
        "iss" => 'http://vis.com',
        "aud" => 'http://vis.com',
        "iat" => 1356999524,
        "nbf" => 1357000000,
        "data" => [
            "id" => $id,
            "email" => $email,
            "role" => $role
        ]
    ];

    return JWT::encode($token, SECRET_KEY, 'HS256');
}

//$jwt = JWT::encode($payload, $key, 'HS256');
//$decoded = JWT::decode($jwt, new Key($key, 'HS256'));

function route($method, $urlData, $formData) {

    // Проверка user'а на логин
    // GET /user/check
    if ($method === 'GET' && count($urlData) === 1 && $urlData[0] === 'check') {

        $headerAuthorization = getallheaders()['authorization'];

        $jwtFromHeader = str_replace('Bearer ', '', $headerAuthorization);

        $decoded = JWT::decode($jwtFromHeader, new Key(SECRET_KEY, 'HS256'));

        $decoded_array = array($decoded);

        $id = $decoded_array[0]->data->id;
        $email = $decoded_array[0]->data->email;
        $role = $decoded_array[0]->data->role;

        $token = jwtEncodeCustom($id, $email, $role);

        if (!empty($token)) {
            // Выводим ответ клиенту
            echo json_encode($token, JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(array(
                'error' => 'Произошла ошибка с созданием токена!'
            ));
            return;
        }

        return;
    }

    // Регистрация пользователя
    // POST /user/registration
    if ($method === 'POST' && count($urlData) === 1 && $urlData[0] === 'registration') {

        $email = $formData['email'];
        $password = $formData['password'];
        $role = $formData['role'];

        $existUser = UserModel::existUser($email);

        if (isset($existUser) && !empty($existUser)) {
            echo json_encode(array(
                'error' => 'Пользователь с таким e-mail существует!'
            ));
            return;
        }

        $hashPassword = password_hash($password,PASSWORD_BCRYPT,array('cost' => 7));

        $userData = UserModel::registration($email, $hashPassword, $role);

        if (empty($userData)) {
            echo json_encode(array(
                'error' => 'Произошла ошибка при создании пользователя!'
            ));
            return;
        }

        $token = jwtEncodeCustom($userData, $email, $role);

        if (!empty($token)) {
            // Выводим ответ клиенту
            echo json_encode($token, JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(array(
                'error' => 'Произошла ошибка с созданием токена!'
            ));
            return;
        }

        return;
    }

    // Авторизация пользователя
    // POST /user/login
    if ($method === 'POST' && count($urlData) === 1 && $urlData[0] === 'login') {

        $email = $formData['email'];
        $password = $formData['password'];

        $existUser = UserModel::existUser($email);

        if (empty($existUser)) {
            echo json_encode(array(
                'error' => 'Пользователя с таким e-mail не существует!'
            ));
            return;
        }

        // $hashPassword = password_hash($password,PASSWORD_BCRYPT, array('cost' => 7));
        $comparedPasswords = password_verify($password, $existUser['password']);

        if (!$comparedPasswords) {
            echo json_encode(array(
                'error' => 'Пароль введён неверно!'
            ));
            return;
        }

        $token = jwtEncodeCustom($existUser['id'], $existUser['email'], $existUser['role']);

        if (!empty($token)) {
            // Выводим ответ клиенту
            echo json_encode($token, JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(array(
                'error' => 'Произошла ошибка с созданием токена!'
            ));
            return;
        }

        return;
    }

    // Возвращаем ошибку
    header('HTTP/1.0 400 Bad Request');
    echo json_encode(array(
        'error' => 'Bad Request'
    ));
}