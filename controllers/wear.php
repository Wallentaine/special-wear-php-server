<?php

require "models/WearModel.php";

function route($method, $urlData, $formData) {

    // Получение всех товаров
    // GET /wear
    if ($method === 'GET' && count($urlData) === 0) {
        // Получаем id товара

        // Вытаскиваем записи из базы...

        $wearData = WearModel::getAll();

        if (isset($wearData) && !empty($wearData)) {
            // Выводим ответ клиенту
            echo json_encode($wearData, JSON_UNESCAPED_UNICODE);
        }
        return;
    }

    // Получение информации о товаре
    // GET /wear/{wearId}
    if ($method === 'GET' && count($urlData) === 1) {
        // Получаем id товара
        $wearId = $urlData[0];

        // Вытаскиваем запись из базы...

        $wearData = WearModel::getOne($wearId);

        if (isset($wearData) && !empty($wearData)) {
            // Выводим ответ клиенту
            echo json_encode($wearData, JSON_UNESCAPED_UNICODE);
        }

        return;
    }

    // Добавление нового товара
    // POST /wear
    if ($method === 'POST' && empty($urlData)) {
        // Добавляем запись в базу...
        $wearData = WearModel::createOne($formData['type'], $formData['wearTime'], $formData['price']);

        if (isset($wearData) && !empty($wearData)) {
            // Выводим ответ клиенту
            echo json_encode($wearData, JSON_UNESCAPED_UNICODE);
        }

        return;
    }

    // Обновление всех данных товара
    // PUT /wear/{wearId}
    if ($method === 'PUT' && count($urlData) === 1) {
        // Получаем id товара
        $wearId = $urlData[0];

        // Обновляем все поля записи по Id в базе...
        $wearData = WearModel::putOne($wearId, $formData['type'], $formData['wearTime'], $formData['price']);

        if (isset($wearData) && !empty($wearData)) {
            // Выводим id обновлённой записи
            echo json_encode($wearData, JSON_UNESCAPED_UNICODE);
        }

        return;
    }

    // Удаление товара
    // DELETE /wear/{wearId}
    if ($method === 'DELETE' && count($urlData) === 1) {
        // Получаем id товара
        $wearId = $urlData[0];

        // Виртуально удаляем запись из базы...
        $wearData = WearModel::deleteOne($wearId);

        if (isset($wearData) && !empty($wearData)) {
            // Выводим id удалённой записи
            echo json_encode($wearData, JSON_UNESCAPED_UNICODE);
        }

        return;
    }

    // Возвращаем ошибку
    header('HTTP/1.0 400 Bad Request');
    echo json_encode(array(
        'error' => 'Bad Request'
    ));

}