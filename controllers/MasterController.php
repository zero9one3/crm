<?php

class MasterController extends Controller
{
    public function index()
    {
        $pdo = Database::getInstance();
        $masters = $pdo->query(
            "SELECT 
                u.id,
                u.name,
                u.email,
                COUNT(o.id) AS orders_count
            FROM users u
            LEFT JOIN orders o 
                ON o.master_id = u.id OR o.issued_master_id = u.id
            WHERE u.role = 'master'
            GROUP BY u.id
            ORDER BY u.name"
        )->fetchAll();


        $this->view('masters/index', ['masters' => $masters]);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pdo = Database::getInstance();

            $stmt = $pdo->prepare(
                "INSERT INTO users (name, email, password, role)
                 VALUES (?, ?, ?, 'master')"
            );

            $stmt->execute([
                $_POST['name'],
                $_POST['email'],
                password_hash($_POST['password'], PASSWORD_DEFAULT)
            ]);

            header('Location: ' . BASE_URL . '/masters');
            exit;
        }

        $this->view('masters/create');
    }
    public function edit()
{
    $id = (int)($_GET['id'] ?? 0);
    if ($id <= 0) {
        http_response_code(404);
        echo 'Мастер не найден';
        return;
    }

    $pdo = Database::getInstance();

    $stmt = $pdo->prepare(
        "SELECT id, name, email, salary_percent
         FROM users
         WHERE id = ? AND role = 'master'"
    );
    $stmt->execute([$id]);
    $master = $stmt->fetch();

    if (!$master) {
        http_response_code(404);
        echo 'Мастер не найден';
        return;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // обновляем основные поля
        $pdo->prepare(
            "UPDATE users
             SET name = ?, email = ?, salary_percent = ?
             WHERE id = ? AND role = 'master'"
        )->execute([
            $_POST['name'],
            $_POST['email'],
            $_POST['salary_percent'],
            $id
        ]);

        // если указан новый пароль — обновляем
        if (!empty($_POST['password'])) {
            $pdo->prepare(
                "UPDATE users SET password = ? WHERE id = ?"
            )->execute([
                password_hash($_POST['password'], PASSWORD_DEFAULT),
                $id
            ]);
        }

        header('Location: ' . BASE_URL . '/masters');
        exit;
    }

    $this->view('masters/edit', ['master' => $master]);
}
    public function delete()
{
    header('Content-Type: application/json');

    if (!Auth::isAdmin()) {
        echo json_encode(['status' => 'error', 'message' => 'Нет прав']);
        return;
    }

    $id = (int)($_POST['id'] ?? 0);
    if ($id <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Неверный ID']);
        return;
    }

    $pdo = Database::getInstance();

    $check = $pdo->prepare(
        "SELECT COUNT(*) FROM orders WHERE master_id = ? OR issued_master_id = ?"
    );
    $check->execute([$id, $id]);

    if ($check->fetchColumn() > 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Нельзя удалить мастера с заказами'
        ]);
        return;
    }

    $pdo->prepare(
        "DELETE FROM users WHERE id = ? AND role = 'master'"
    )->execute([$id]);

    echo json_encode(['status' => 'ok']);
}


}
