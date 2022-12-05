<?php

require 'models/ReceivingModel.php';

function stringArrayToArray($str) {
    $str = str_replace('[', '', $str);
    $str = str_replace(']', '', $str);
    $str = str_replace(' ', '', $str);

    return explode(',', $str);
}

function route($method, $urlData, $formData) {

    // Получение всех Получений
    // GET /receiving
    if ($method === 'GET' && count($urlData) === 0) {

        // Вытаскиваем записи из базы...
        $receivingData = ReceivingModel::getAll();

        if (isset($receivingData) && !empty($receivingData)) {
            // Выводим ответ клиенту
            echo json_encode($receivingData, JSON_UNESCAPED_UNICODE);
        }

        return;
    }

    // Проверка user'а на логин
    // GET /receiving/report
    if ($method === 'GET' && count($urlData) === 1 && $urlData[0] === 'report') {

        // Вытаскиваем записи из базы...
        $receivingData = ReceivingModel::getReport($formData['month'], $formData['year']);

        if (isset($receivingData) && !empty($receivingData)) {
            // Выводим ответ клиенту
            echo json_encode($receivingData, JSON_UNESCAPED_UNICODE);
        }

        return;
    }

    // Получение информации о получениях
    // GET /receiving/{receivingId}
    if ($method === 'GET' && count($urlData) === 1) {
        // Получаем id должности
        $receivingId = $urlData[0];

        // Вытаскиваем запись из базы...
        $receivingData = ReceivingModel::getOne($receivingId);

        if (isset($receivingData) && !empty($receivingData)) {
            // Выводим ответ клиенту
            echo json_encode($receivingData, JSON_UNESCAPED_UNICODE);
        }

        return;
    }

    // Добавление нового получения
    // POST /receiving
    if ($method === 'POST' && empty($urlData)) {

        $formData['wears'] = stringArrayToArray($formData['wears']);

        // Добавляем запись в базу...
        $receivingData = ReceivingModel::createOne($formData['workerId'], $formData['receivingDate'], $formData['sign'], $formData['wears']);

        if (isset($receivingData) && !empty($receivingData)) {
            // Выводим ответ клиенту
            echo json_encode($receivingData, JSON_UNESCAPED_UNICODE);
        }

        return;
    }

    // Обновление всех данных о получении
    // PUT /receiving/{receivingId}
    if ($method === 'PUT' && count($urlData) === 1) {
        // Получаем id должности
        $receivingId = $urlData[0];

        // Обновляем все поля записи по Id в базе...
        $receivingData = ReceivingModel::putOne($receivingId, $formData['workerId'], $formData['receivingDate'], $formData['sign'], $formData['wears']);

        if (isset($receivingData) && !empty($receivingData)) {
            // Выводим id обновлённой записи
            echo json_encode($receivingData, JSON_UNESCAPED_UNICODE);
        }

        return;
    }

    // Виртуальное удаление получения
    // DELETE /receiving/{receivingId}
    if ($method === 'DELETE' && count($urlData) === 1) {
        // Получаем id должности
        $receivingId = $urlData[0];

        // Виртуально удаляем запись из базы...
        $receivingData = ReceivingModel::deleteOne($receivingId);

        if (isset($receivingData) && !empty($receivingData)) {
            // Выводим id удалённой записи
            echo json_encode($receivingData, JSON_UNESCAPED_UNICODE);
        }

        return;
    }

    // Возвращаем ошибку
    header('HTTP/1.0 400 Bad Request');
    echo json_encode(array(
        'error' => 'Bad Request'
    ));

}