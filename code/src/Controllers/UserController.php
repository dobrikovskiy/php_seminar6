<?php

// Подключаемся к базе данных
$servername = "database";
$username = "application_user";
$password = "geekbrains!23";
$dbname = "application1";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Устанавливаем режим обработки ошибок
    echo "Соединение установлено";
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

function updateUser($id, $name) {
    global $conn;
    
    if (!is_numeric($id)) {
        return "ID должен быть числом.";
    }
    
    try {
        // Проверяем существование пользователя
        $stmt = $conn->prepare("SELECT * FROM users WHERE id_user=:id");
        $stmt->execute(['id' => $id]);
        
        if ($stmt->rowCount() == 0) {
            return "Пользователь с таким ID не найден.";
        }
        
        // Обновляем только поле name
        $stmt = $conn->prepare("UPDATE users SET name=:name WHERE id_user=:id");
        $stmt->execute(['name' => $name, 'id' => $id]);
        
        return "Имя пользователя успешно обновлено.";
    } catch (PDOException $e) {
        return "Ошибка при обновлении пользователя: " . $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id']) && isset($_GET['name'])) {
        echo updateUser($_GET['id'], $_GET['name']);
    } else {
        echo "Не указаны обязательные параметры.";
    }
}

function deleteUser($id) {
    global $conn;
    
    if (!is_numeric($id)) {
        return "ID должен быть числом.";
    }
    
    try {
        // Проверяем существование пользователя
        $stmt = $conn->prepare("SELECT * FROM users WHERE id=:id");
        $stmt->execute(['id' => $id]);
        
        if ($stmt->rowCount() == 0) {
            return "Пользователь с таким ID не найден.";
        }
        
        // Удаление пользователя
        $stmt = $conn->prepare("DELETE FROM users WHERE id=:id");
        $stmt->execute(['id' => $id]);
        
        return "Пользователь удалён.";
    } catch (PDOException $e) {
        return "Ошибка при удалении пользователя: " . $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        echo deleteUser($_GET['id']);
    } else {
        echo "Не указан параметр ID.";
    }
}
// namespace Geekbrains\Application1\Domain\Controllers;

// use Geekbrains\Application1\Application\Render;
// use Geekbrains\Application1\Domain\Models\User;

// class UserController {

//     public function actionIndex(): string {
//         $users = User::getAllUsersFromStorage();
        
//         $render = new Render();

//         if(!$users){
//             return $render->renderPage(
//                 'user-empty.tpl', 
//                 [
//                     'title' => 'Список пользователей в хранилище',
//                     'message' => "Список пуст или не найден"
//                 ]);
//         }
//         else{
//             return $render->renderPage(
//                 'user-index.tpl', 
//                 [
//                     'title' => 'Список пользователей в хранилище',
//                     'users' => $users
//                 ]);
//         }
//     }

//     public function actionSave(): string {
//         if(User::validateRequestData()) {
//             $user = new User();
//             $user->setParamsFromRequestData();
//             $user->saveToStorage();

//             $render = new Render();

//             return $render->renderPage(
//                 'user-created.tpl', 
//                 [
//                     'title' => 'Пользователь создан',
//                     'message' => "Создан пользователь " . $user->getUserName() . " " . $user->getUserLastName()
//                 ]);
//         }
//         else {
//             throw new \Exception("Переданные данные некорректны");
//         }
//     }

//     public function actionUpdate(): string {
//         if(User::exists($_GET['id'])) {
//             $user = new User();
//             $user->setUserId($_GET['id']);
            
//             $arrayData = [];

//             if(isset($_GET['name']))
//                 $arrayData['user_name'] = $_GET['name'];

//             if(isset($_GET['lastname'])) {
//                 $arrayData['user_lastname'] = $_GET['lastname'];
//             }
            
//             $user->updateUser($arrayData);
//         }
//         else {
//             throw new \Exception("Пользователь не существует");
//         }

//         $render = new Render();
//         return $render->renderPage(
//             'user-created.tpl', 
//             [
//                 'title' => 'Пользователь обновлен',
//                 'message' => "Обновлен пользователь " . $user->getUserId()
//             ]);
//     }

//     public function actionDelete(): string {
//         if(User::exists($_GET['id'])) {
//             User::deleteFromStorage($_GET['id']);

//             $render = new Render();
            
//             return $render->renderPage(
//                 'user-removed.tpl', []
//             );
//         }
//         else {
//             throw new \Exception("Пользователь не существует");
//         }
//     }

    
//}