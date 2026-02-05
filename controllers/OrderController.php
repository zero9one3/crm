<?php

class OrderController extends Controller
{
    public function index()
    {
        $pdo = Database::getInstance();

        if (Auth::isMaster()) {
            $stmt = $pdo->prepare(
                "SELECT o.*, c.name AS client_name
                 FROM orders o
                 JOIN clients c ON c.id = o.client_id
                 WHERE o.master_id = ?
                 ORDER BY o.created_at DESC"
            );
            $stmt->execute([Auth::user()['id']]);
        } else {
            $stmt = $pdo->query(
                "SELECT o.*, c.name AS client_name
                 FROM orders o
                 JOIN clients c ON c.id = o.client_id
                 ORDER BY o.created_at DESC"
            );
        }

        $orders = $stmt->fetchAll();

        $this->view('orders/index', ['orders' => $orders]);
    }

public function create()
{
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        $pdo = Database::getInstance();

        // === клиент ===
        $phone = trim($_POST['client_phone']);
        $name  = trim($_POST['client_name']);

        $stmt = $pdo->prepare("SELECT id FROM clients WHERE phone = ?");
        $stmt->execute([$phone]);
        $clientId = $stmt->fetchColumn();

        if (!$clientId) {
            $stmt = $pdo->prepare(
                "INSERT INTO clients (name, phone) VALUES (?, ?)"
            );
            $stmt->execute([$name, $phone]);
            $clientId = $pdo->lastInsertId();
        }

        // === состояние устройства ===
        $conditionState = json_encode(
            $_POST['condition_state'] ?? [],
            JSON_UNESCAPED_UNICODE
        );

        // === заказ ===
        $orderId = Order::create([
            'client_id' => (int)$clientId,
            'master_id' => $_POST['master_id'] !== '' ? (int)$_POST['master_id'] : null,
            'device'    => trim($_POST['device']),
            'problem'   => trim($_POST['problem']),
            'price'     => (float)$_POST['price'],
            'cost'      => (float)$_POST['cost'],
            'status'    => 'new',
            'condition_state' => $conditionState
        ]);

        header('Location: ' . BASE_URL . '/orders/edit?id=' . $orderId);
        exit;
    }

    // GET
    $pdo = Database::getInstance();
    $masters = $pdo->query(
        "SELECT id, name FROM users WHERE role='master' ORDER BY name"
    )->fetchAll();

    $this->view('orders/create', ['masters' => $masters]);
}



    public function edit()
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            http_response_code(404);
            echo 'Заказ не найден';
            return;
        }

        $order = Order::findWithClient($id);
        if (!$order) {
            http_response_code(404);
            echo 'Заказ не найден';
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // ===== Блокировки после issued =====
            if ($order['status'] === 'issued') {
                $_POST['price']  = $order['price'];
                $_POST['cost']   = $order['cost'];
                $_POST['status'] = 'issued';
            }

            // ===== Внешнее состояние (фиксируется один раз) =====
            if (empty($order['condition_state'])) {
                $conditionState = $_POST['condition_state'] ?? [];
                if (!is_array($conditionState)) {
                    $conditionState = [];
                }
                $conditionJson = json_encode($conditionState, JSON_UNESCAPED_UNICODE);
            } else {
                $conditionJson = $order['condition_state'];
            }

$masterId = $_POST['master_id'] ?? $order['master_id'];


Order::update($id, [
    'master_id' => $masterId,
    'device'    => trim($_POST['device']),
    'problem'   => trim($_POST['problem']),
    'price'     => (float)$_POST['price'],
    'cost'      => (float)$_POST['cost'],
    'status'    => $_POST['status'],
    'condition_state' => $conditionJson
]);

// ===== ИСТОРИЯ: смена мастера =====
$oldMasterId = $order['master_id'];
$newMasterId = $_POST['master_id'] ?? $order['master_id'];

if ((int)$oldMasterId !== (int)$newMasterId) {

    $pdo = Database::getInstance();

    $stmt = $pdo->prepare("SELECT name FROM users WHERE id = ?");
    $stmt->execute([$oldMasterId]);
    $oldName = $stmt->fetchColumn() ?: '—';

    $stmt->execute([$newMasterId]);
    $newName = $stmt->fetchColumn() ?: '—';

OrderHistory::add(
    $id,
    'master_id',
    (string)$oldMasterId,
    (string)$newMasterId
);

}
if ($order['status'] !== $_POST['status']) {
    OrderHistory::add(
        $id,
        'status',
        $order['status'],
        $_POST['status']
    );
}
if ((float)$order['price'] !== (float)$_POST['price']) {
    OrderHistory::add(
        $id,
        'price',
        (string)$order['price'],
        (string)$_POST['price']
    );
}
if ((float)$order['cost'] !== (float)$_POST['cost']) {
    OrderHistory::add(
        $id,
        'cost',
        (string)$order['cost'],
        (string)$_POST['cost']
    );
}



            header('Location: ' . BASE_URL . '/orders/edit?id=' . $id);
            exit;
        }

        $history = OrderHistory::getByOrder($id);

$pdo = Database::getInstance();

$masters = $pdo->query(
    "SELECT id, name FROM users WHERE role = 'master' ORDER BY name"
)->fetchAll();


$this->view('orders/edit', [
    'order'   => $order,
    'history' => $history,
    'masters' => $masters
]);


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

    $order = Order::find($id);
    if (!$order) {
        echo json_encode(['status' => 'error', 'message' => 'Заказ не найден']);
        return;
    }

    if ($order['status'] === 'issued') {
        echo json_encode([
            'status' => 'error',
            'message' => 'Нельзя удалить выданный заказ'
        ]);
        return;
    }

    Order::delete($id);

    echo json_encode(['status' => 'ok', 'message' => 'Удалено']);
}


    public function receipt()
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            echo 'Заказ не найден';
            return;
        }

        $order = Order::findWithClient($id);
        if (!$order) {
            echo 'Заказ не найден';
            return;
        }

        require __DIR__ . '/../views/orders/receipt.php';
    }
}
