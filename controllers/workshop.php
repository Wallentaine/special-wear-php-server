<?php

require 'models/WorkshopModel.php';

function route($method, $urlData, $formData) {

    // Получение всех цехов
    // GET /workshop
    if ($method === 'GET' && count($urlData) === 0) {

        // Вытаскиваем записи из базы...
        $workshopData = WorkshopModel::getAll();

        if (isset($workshopData) && !empty($workshopData)) {
            // Выводим ответ клиенту
            echo json_encode($workshopData, JSON_UNESCAPED_UNICODE);
        }

        return;
    }

    // Получение информации о цехе
    // GET /workshop/{workshopId}
    if ($method === 'GET' && count($urlData) === 1) {
        // Получаем id товара
        $workshopId = $urlData[0];

        // Вытаскиваем запись из базы...
        $workshopData = WorkshopModel::getOne($workshopId);

        if (isset($workshopData) && !empty($workshopData)) {
            // Выводим ответ клиенту
            echo json_encode($workshopData, JSON_UNESCAPED_UNICODE);
        }

        return;
    }

    // Добавление нового цеха
    // POST /workshop
    if ($method === 'POST' && empty($urlData)) {
        // Добавляем запись в базу...
        $workshopData = WorkshopModel::createOne($formData['title']);

        if (isset($workshopData) && !empty($workshopData)) {
            // Выводим ответ клиенту
            echo json_encode($workshopData, JSON_UNESCAPED_UNICODE);
        }

        return;
    }

    // Обновление всех данных о цехе
    // PUT /workshop/{workshopId}
    if ($method === 'PUT' && count($urlData) === 1) {
        // Получаем id товара
        $workshopId = $urlData[0];

        // Обновляем все поля записи по Id в базе...
        $workshopData = WorkshopModel::putOne($workshopId, $formData['title']);

        if (isset($workshopData) && !empty($workshopData)) {
            // Выводим id обновлённой записи
            echo json_encode($workshopData, JSON_UNESCAPED_UNICODE);
        }

        return;
    }

    // Виртуальное удаление цеха
    // DELETE /workshop/{workshopId}
    if ($method === 'DELETE' && count($urlData) === 1) {
        // Получаем id товара
        $workshopId = $urlData[0];

        // Виртуально удаляем запись из базы...
        $workshopData = WorkshopModel::deleteOne($workshopId);

        if (isset($workshopData) && !empty($workshopData)) {
            // Выводим id удалённой записи
            echo json_encode($workshopData, JSON_UNESCAPED_UNICODE);
        }

        return;
    }

    // Возвращаем ошибку
    header('HTTP/1.0 400 Bad Request');
    echo json_encode(array(
        'error' => 'Bad Request'
    ));

}