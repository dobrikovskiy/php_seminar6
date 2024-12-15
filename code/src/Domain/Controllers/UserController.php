<?php

namespace Geekbrains\Application1\Domain\Controllers;

use Geekbrains\Application1\Infrastructure\Storage;
use Exception;

class UserController
{
    /** @var Storage */
    private $storage;

    public function __construct()
    {
        $this->storage = Storage::getInstance();
    }

    /**
     * Действие для добавления нового пользователя.
     *
     * @param string $name Имя пользователя.
     * @param string $lastname Фамилия пользователя.
     * @param string $birthday Дата рождения пользователя.
     * @throws Exception
     * @return string Сообщение о результате операции.
     */
    
    public function actionSave(string $name, string $lastname, string $birthday): string
    {
        try {
            // Преобразуем дату рождения в timestamp
            $timestamp = strtotime($birthday);

            // Вставляем данные о пользователе
            $result = $this->storage->addUser($name, $lastname, $timestamp);

            return "Пользователь с именем $name и фамилией $lastname сохранён.";
        } catch (\Throwable $e) {
            throw new Exception("Ошибка при сохранении пользователя: " . $e->getMessage(), 500, $e);
        }
    }

    /**
     * Действие для обновления имени пользователя.
     *
     * @param int $id Идентификатор пользователя.
     * @param string $newName Новое имя пользователя.
     * @throws Exception
     * @return string Сообщение о результате операции.
     */
    public function actionUpdate(int $id, string $newName): string
    {
        try {
            // Проверяем существование пользователя
            $user = $this->storage->findUserById($id);
            if (!$user) {
                throw new Exception("Пользователь с таким ID не найден.", 404);
            }

            // Обновляем имя пользователя
            $this->storage->updateUserName($id, $newName);

            return "Имя пользователя с ID $id успешно обновлено на $newName.";
        } catch (\Throwable $e) {
            throw new Exception("Ошибка при обновлении пользователя: " . $e->getMessage(), 500, $e);
        }
    }

    /**
     * Действие для удаления пользователя.
     *
     * @param int $id Идентификатор пользователя.
     * @throws Exception
     * @return string Сообщение о результате операции.
     */
    public function actionDelete(int $id): string
    {
        try {
            // Проверяем существование пользователя
            $user = $this->storage->findUserById($id);
            if (!$user) {
                throw new Exception("Пользователь с таким ID не найден.", 404);
            }

            // Удаление пользователя
            $this->storage->deleteUser($id);

            return "Пользователь с ID $id был удалён.";
        } catch (\Throwable $e) {
            throw new Exception("Ошибка при удалении пользователя: " . $e->getMessage(), 500, $e);
        }
    }
}
