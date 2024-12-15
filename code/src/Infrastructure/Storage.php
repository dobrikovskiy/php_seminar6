<?php

namespace Geekbrains\Application1\Infrastructure;

use Geekbrains\Application1\Application\Application;
use PDO;

class Storage
{
    private static ?Storage $instance = null; // Статическое свойство для хранения экземпляра
    private PDO $connection;

    public function __construct() {
        $this->connection = new PDO(
            Application::$config->get()['database']['DSN'],
            Application::$config->get()['database']['USER'],
            Application::$config->get()['database']['PASSWORD'],
            array(
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
            )
        );
    }

    public static function getInstance(): Storage {
        if (self::$instance === null) {
            self::$instance = new Storage();
        }
        return self::$instance;
    }

    public function findUserById(int $id): ?array {
        $stmt = $this->connection->prepare("SELECT * FROM users WHERE id_user = :id");
        $stmt->execute(['id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addUser(string $name, string $lastname, int $birthdayTimestamp): bool {
        $stmt = $this->connection->prepare("INSERT INTO users (user_name, user_lastname, user_birthday_timestamp) VALUES (:name, :lastname, :timestamp)");
        return $stmt->execute([
            ':name' => $name,
            ':lastname' => $lastname,
            ':timestamp' => $birthdayTimestamp
        ]);
    }

    public function updateUserName(int $id, string $newName): bool {
        $stmt = $this->connection->prepare("UPDATE users SET user_name=:name WHERE id_user=:id");
        return $stmt->execute([
            ':name' => $newName,
            ':id' => $id
        ]);
    }

    public function deleteUser(int $id): bool {
        $stmt = $this->connection->prepare("DELETE FROM users WHERE id_user=:id");
        return $stmt->execute([':id' => $id]);
    }

    public function get(): PDO {
        return $this->connection;
    }
}