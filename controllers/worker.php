<?php

require 'models/WorkerModel.php';

function route($method, $urlData, $formData) {

    // Получение всех сотрудников
    // GET /worker
    if ($method === 'GET' && count($urlData) === 0) {

        // Вытаскиваем записи из базы...
        $workerData = WorkerModel::getAll();

        if (isset($workerData) && !empty($workerData)) {
            // Выводим ответ клиенту
            echo json_encode($workerData, JSON_UNESCAPED_UNICODE);
        }

        return;
    }

    // GET /worker/receivingByMonth
    if ($method === 'GET' && count($urlData) === 1 && $urlData[0] === 'receivingByMonth') {

        // Вытаскиваем записи из базы...
        $workerData = WorkerModel::getWorkersReceivingByMonth($formData['month']);

        if (isset($workerData) && !empty($workerData)) {
            // Выводим ответ клиенту
            echo json_encode($workerData, JSON_UNESCAPED_UNICODE);
        }

        return;
    }

    // Получение информации о сотрудниках цеха
    // GET /worker/workshop/{workshopId}
    if ($method === 'GET' && count($urlData) === 2 && $urlData[0] === 'workshop') {
        // Получаем id товара
        $workshopId = $urlData[1];

        // Вытаскиваем записи из базы...
        $workersData = WorkerModel::getWorkersByWorkshop($workshopId);

        if (isset($workersData) && !empty($workersData)) {
            // Выводим ответ клиенту
            echo json_encode($workersData, JSON_UNESCAPED_UNICODE);
        }

        return;
    }

    // GET /worker/discountbetween
    if ($method === 'GET' && count($urlData) === 1 && $urlData[0] === 'discountbetween') {

        // Вытаскиваем записи из базы...
        $workerData = WorkerModel::getWorkersWhereDiscountBetween($formData['from'], $formData['before']);

        if (isset($workerData) && !empty($workerData)) {
            // Выводим ответ клиенту
            echo json_encode($workerData, JSON_UNESCAPED_UNICODE);
        }

        return;
    }

    // GET /worker/receiving
    if ($method === 'GET' && count($urlData) === 1 && $urlData[0] === 'receiving')  {

        // Вытаскиваем общие данные о пользователе из базы...
        $workerData = WorkerModel::getWorkersReceiving();

        // Выводим ответ клиенту
        if (isset($workerData) && !empty($workerData)) {
            // Выводим ответ клиенту
            echo json_encode($workerData, JSON_UNESCAPED_UNICODE);
        }

        return;
    }

    // GET /worker/notreceiving
    if ($method === 'GET' && count($urlData) === 1 && $urlData[0] === 'notreceiving')  {

        // Вытаскиваем общие данные о пользователе из базы...
        $workerData = WorkerModel::getWorkersNotReceiving();

        // Выводим ответ клиенту
        if (isset($workerData) && !empty($workerData)) {
            // Выводим ответ клиенту
            echo json_encode($workerData, JSON_UNESCAPED_UNICODE);
        }

        return;
    }

    // GET /worker/maxdiscount
    if ($method === 'GET' && count($urlData) === 1 && $urlData[0] === 'maxdiscount')  {

        // Вытаскиваем общие данные о пользователе из базы...
        $workerData = WorkerModel::getWorkersWithMaxDiscount();

        // Выводим ответ клиенту
        if (isset($workerData) && !empty($workerData)) {
            // Выводим ответ клиенту
            echo json_encode($workerData, JSON_UNESCAPED_UNICODE);
        }

        return;
    }

    // Получение информации о сотруднике
    // GET /worker/{workerId}
    if ($method === 'GET' && count($urlData) === 1) {
        // Получаем id товара
        $workerId = $urlData[0];

        // Вытаскиваем запись из базы...
        $workerData = WorkerModel::getOne($workerId);

        if (isset($workerData) && !empty($workerData)) {
            // Выводим ответ клиенту
            echo json_encode($workerData, JSON_UNESCAPED_UNICODE);
        }

        return;
    }

    // GET /worker/{workerId}/receiving
    if ($method === 'GET' && count($urlData) === 2 && $urlData[1] === 'receiving')  {
        // Получаем id сотрудника
        $workerId = $urlData[0];

        // Вытаскиваем общие данные о пользователе из базы...
        $workerData = WorkerModel::getWorkerReceiving($workerId);

        // Выводим ответ клиенту
        if (isset($workerData) && !empty($workerData)) {
            // Выводим ответ клиенту
            echo json_encode($workerData, JSON_UNESCAPED_UNICODE);
        }

        return;
    }

    // GET /worker/{workerId}/getBoss
    if ($method === 'GET' && count($urlData) === 2 && $urlData[1] === 'getBoss')  {
        // Получаем id сотрудника
        $workerId = $urlData[0];

        // Вытаскиваем общие данные о пользователе из базы...
        $workerData = WorkerModel::getWorkerBoss($workerId);

        // Выводим ответ клиенту
        if (isset($workerData) && !empty($workerData)) {
            // Выводим ответ клиенту
            echo json_encode($workerData, JSON_UNESCAPED_UNICODE);
        }

        return;
    }

    // Добавление новой должности
    // POST /worker
    if ($method === 'POST' && empty($urlData)) {
        // Добавляем запись в базу...
        $workerData = WorkerModel::createOne($formData['workshopId'], $formData['postId'], $formData['fullName']);

        if (isset($workerData) && !empty($workerData)) {
            // Выводим ответ клиенту
            echo json_encode($workerData, JSON_UNESCAPED_UNICODE);
        }

        return;
    }

    // Обновление всех данных о должности
    // PUT /worker/{workerId}
    if ($method === 'PUT' && count($urlData) === 1) {
        // Получаем id товара
        $workerId = $urlData[0];

        // Обновляем все поля записи по Id в базе...
        $workerData = WorkerModel::putOne($workerId, $formData['workshopId'], $formData['postId'], $formData['fullName']);

        if (isset($workerData) && !empty($workerData)) {
            // Выводим id обновлённой записи
            echo json_encode($workerData, JSON_UNESCAPED_UNICODE);
        }

        return;
    }

    // Виртуальное удаление должности
    // DELETE /worker/{workerId}
    if ($method === 'DELETE' && count($urlData) === 1) {
        // Получаем id товара
        $workerId = $urlData[0];

        // Виртуально удаляем запись из базы...
        $workerData = WorkerModel::deleteOne($workerId);

        if (isset($workerData) && !empty($workerData)) {
            // Выводим id удалённой записи
            echo json_encode($workerData, JSON_UNESCAPED_UNICODE);
        }

        return;
    }

    // Возвращаем ошибку
    header('HTTP/1.0 400 Bad Request');
    echo json_encode(array(
        'error' => 'Bad Request'
    ));

}