<?php

class ClientController extends Controller
{
    public function index()
    {
        $pdo = Database::getInstance();

        $search = trim($_GET['q'] ?? '');

        if ($search !== '') {
            $stmt = $pdo->prepare(
                "SELECT c.*,
                        COUNT(o.id) AS orders_count
                 FROM clients c
                 LEFT JOIN orders o ON o.client_id = c.id
                 WHERE c.name LIKE ? OR c.phone LIKE ?
                 GROUP BY c.id
                 ORDER BY c.created_at DESC"
            );
            $stmt->execute([
                "%$search%",
                "%$search%"
            ]);
        } else {
            $stmt = $pdo->query(
                "SELECT c.*,
                        COUNT(o.id) AS orders_count
                 FROM clients c
                 LEFT JOIN orders o ON o.client_id = c.id
                 GROUP BY c.id
                 ORDER BY c.created_at DESC"
            );
        }

        $clients = $stmt->fetchAll();

        $this->view('clients/index', [
            'clients' => $clients,
            'search'  => $search
        ]);
    }

    public function show()
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            echo 'Клиент не найден';
            return;
        }

        $pdo = Database::getInstance();

        $stmt = $pdo->prepare("SELECT * FROM clients WHERE id = ?");
        $stmt->execute([$id]);
        $client = $stmt->fetch();

        if (!$client) {
            echo 'Клиент не найден';
            return;
        }

        $stmt = $pdo->prepare(
            "SELECT *
             FROM orders
             WHERE client_id = ?
             ORDER BY created_at DESC"
        );
        $stmt->execute([$id]);
        $orders = $stmt->fetchAll();

        $this->view('clients/view', [
            'client' => $client,
            'orders' => $orders
        ]);
    }

    public function edit()
    {
        $id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
        if ($id <= 0) {
            echo 'Клиент не найден';
            return;
        }

        $pdo = Database::getInstance();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $stmt = $pdo->prepare(
                "UPDATE clients
                 SET name = ?, phone = ?
                 WHERE id = ?"
            );
            $stmt->execute([
                trim($_POST['name']),
                trim($_POST['phone']),
                $id
            ]);

            header('Location: ' . BASE_URL . '/clients/view?id=' . $id);
            exit;
        }

        $stmt = $pdo->prepare("SELECT * FROM clients WHERE id = ?");
        $stmt->execute([$id]);
        $client = $stmt->fetch();

        if (!$client) {
            echo 'Клиент не найден';
            return;
        }

        $this->view('clients/edit', ['client' => $client]);
    }
}
