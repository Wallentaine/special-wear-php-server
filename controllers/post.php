<?php

require('models/PostModel.php');

function route($method, $urlData, $formData) {

    // Получение всех Должностей
    // GET /post
    if ($method === 'GET' && count($urlData) === 0) {

        // Вытаскиваем записи из базы...
        $postData = PostModel::getAll();

        if (isset($postData) && !empty($postData)) {
            // Выводим ответ клиенту
            echo json_encode($postData, JSON_UNESCAPED_UNICODE);
        }

        return;
    }

    // Получение информации о должности
    // GET /post/{postId}
    if ($method === 'GET' && count($urlData) === 1) {
        // Получаем id должности
        $postId = $urlData[0];

        // Вытаскиваем запись из базы...
        $postData = PostModel::getOne($postId);

        if (isset($postData) && !empty($postData)) {
            // Выводим ответ клиенту
            echo json_encode($postData, JSON_UNESCAPED_UNICODE);
        }

        return;
    }

    // Добавление новой должности
    // POST /post
    if ($method === 'POST' && empty($urlData)) {
        // Добавляем запись в базу...
            $postData = PostModel::createOne($formData['title'], $formData['discountPercent']);

        if (isset($postData) && !empty($postData)) {
            // Выводим ответ клиенту
            echo json_encode($postData, JSON_UNESCAPED_UNICODE);
        }

        return;
    }

    // Обновление всех данных о должности
    // PUT /post/{postId}
    if ($method === 'PUT' && count($urlData) === 1) {
        // Получаем id должности
        $postId = $urlData[0];

        // Обновляем все поля записи по Id в базе...
        $postData = PostModel::putOne($postId, $formData['title'], $formData['discountPercent']);

        if (isset($postData) && !empty($postData)) {
            // Выводим id обновлённой записи
            echo json_encode($postData, JSON_UNESCAPED_UNICODE);
        }

        return;
    }

    // Виртуальное удаление должности
    // DELETE /post/{postId}
    if ($method === 'DELETE' && count($urlData) === 1) {
        // Получаем id должности
        $postId = $urlData[0];

        // Виртуально удаляем запись из базы...
        $postData = PostModel::deleteOne($postId);

        if (isset($postData) && !empty($postData)) {
            // Выводим id удалённой записи
            echo json_encode($postData, JSON_UNESCAPED_UNICODE);
        }

        return;
    }

    // Возвращаем ошибку
    header('HTTP/1.0 400 Bad Request');
    echo json_encode(array(
        'error' => 'Bad Request'
    ));
}