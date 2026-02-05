<?php

class Order
{
    public static function all(): array
    {
        $pdo = Database::getInstance();

        if (Auth::isAdmin()) {
            $stmt = $pdo->query(
                "SELECT o.*, c.name AS client_name
                 FROM orders o
                 JOIN clients c ON c.id = o.client_id
                 ORDER BY o.created_at DESC"
            );
            return $stmt->fetchAll();
        }

        // master — только свои заказы
        $stmt = $pdo->prepare(
            "SELECT o.*, c.name AS client_name
             FROM orders o
             JOIN clients c ON c.id = o.client_id
             WHERE o.master_id = ?
             ORDER BY o.created_at DESC"
        );
        $stmt->execute([Auth::user()['id']]);

        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $pdo = Database::getInstance();

        if (Auth::isAdmin()) {
            $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
            $stmt->execute([$id]);
        } else {
            $stmt = $pdo->prepare(
                "SELECT * FROM orders WHERE id = ? AND master_id = ?"
            );
            $stmt->execute([$id, Auth::user()['id']]);
        }

        return $stmt->fetch() ?: null;
    }

public static function create(array $data): int
{
    $pdo = Database::getInstance();

    $stmt = $pdo->prepare(
        "INSERT INTO orders
         (client_id, master_id, device, problem, price, cost, status)
         VALUES (?, ?, ?, ?, ?, ?, 'new')"
    );

    $stmt->execute([
        $data['client_id'],
        $data['master_id'],
        $data['device'],
        $data['problem'],
        $data['price'],
        $data['cost']
    ]);

    return (int)$pdo->lastInsertId();
}


public static function update(int $id, array $data): bool
{
    $pdo = Database::getInstance();

    // текущее состояние заказа
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->execute([$id]);
    $current = $stmt->fetch();

    if (!$current) {
        return false;
    }
    // 🔒 Блокировка финансов после issued
if ($current['status'] === 'issued') {

    // запрещаем менять цену и себестоимость
    $data['price'] = $current['price'];
    $data['cost']  = $current['cost'];

    // запрещаем откат статуса
    if ($data['status'] !== 'issued') {
        $data['status'] = 'issued';
    }
}

// если статус стал issued — фиксируем мастера
if ($current['status'] !== 'issued' && $data['status'] === 'issued') {

    // OrderHistory::add(
    //     $id,
    //     'issued',
    //     'not_issued',
    //     'issued'
    // );

    $data['issued_master_id'] = $current['master_id'];
}

    // ===== ЛОГИРОВАНИЕ ИЗМЕНЕНИЙ =====

    if ($current['status'] !== $data['status']) {
        OrderHistory::add(
            $id,
            'status',
            $current['status'],
            $data['status']
        );
    }

    if ((int)$current['master_id'] !== (int)$data['master_id']) {
        OrderHistory::add(
            $id,
            'master_id',
            (string)$current['master_id'],
            (string)$data['master_id']
        );
    }

    // 💰 цена
    if ((float)$current['price'] !== (float)$data['price']) {
        OrderHistory::add(
            $id,
            'price',
            (string)$current['price'],
            (string)$data['price']
        );
    }

    // 💸 себестоимость
    if ((float)$current['cost'] !== (float)$data['cost']) {
        OrderHistory::add(
            $id,
            'cost',
            (string)$current['cost'],
            (string)$data['cost']
        );
    }

    // ===== ОБНОВЛЕНИЕ ЗАКАЗА =====

$stmt = $pdo->prepare(
    "UPDATE orders
     SET master_id = ?, issued_master_id = ?, device = ?, problem = ?,
         price = ?, cost = ?, status = ?
     WHERE id = ?"
);

return $stmt->execute([
    $data['master_id'],
    $data['issued_master_id'] ?? $current['issued_master_id'],
    $data['device'],
    $data['problem'],
    $data['price'],
    $data['cost'],
    $data['status'],
    $id
]);

}



    public static function filter(array $filters): array
    {
        $pdo = Database::getInstance();

        $sql = "
            SELECT o.*, c.name AS client_name
            FROM orders o
            JOIN clients c ON c.id = o.client_id
            WHERE 1=1
        ";

        $params = [];

        // 📌 статус
        if (!empty($filters['status'])) {
            $sql .= " AND o.status = ?";
            $params[] = $filters['status'];
        }

        // 📅 дата от
        if (!empty($filters['date_from'])) {
            $sql .= " AND o.created_at >= ?";
            $params[] = $filters['date_from'] . ' 00:00:00';
        }

        // 📅 дата до
        if (!empty($filters['date_to'])) {
            $sql .= " AND o.created_at <= ?";
            $params[] = $filters['date_to'] . ' 23:59:59';
        }

        // 👤 клиент
        if (!empty($filters['client'])) {
            $sql .= " AND (c.name LIKE ? OR c.phone LIKE ?)";
            $params[] = '%' . $filters['client'] . '%';
            $params[] = '%' . $filters['client'] . '%';
        }

        $sql .= " ORDER BY o.created_at DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }
public static function delete(int $id): bool
{
    if (!Auth::isAdmin()) {
        return false;
    }

    $pdo = Database::getInstance();
    $stmt = $pdo->prepare("DELETE FROM orders WHERE id = ?");
    return $stmt->execute([$id]);
}

public static function createWithClient(array $data): bool
{
    $pdo = Database::getInstance();

    $clientId = Client::findOrCreate(
        $data['client_name'] ?? '',
        $data['client_phone']
    );

    $stmt = $pdo->prepare(
        "INSERT INTO orders 
        (client_id, master_id, device, problem, price, cost, status)
        VALUES (?, ?, ?, ?, ?, ?, 'new')"
    );

    return $stmt->execute([
        $clientId,
        $data['master_id'],
        $data['device'],
        $data['problem'],
        (float)$data['price'],
        (float)$data['cost']
    ]);
}

public static function findWithClient(int $id): ?array
{
    $pdo = Database::getInstance();

    $stmt = $pdo->prepare(
        "SELECT 
            o.*,
            c.name AS client_name,
            c.phone AS client_phone,
            u.name AS master_name
         FROM orders o
         JOIN clients c ON c.id = o.client_id
         LEFT JOIN users u ON u.id = o.master_id
         WHERE o.id = ?"
    );
    $stmt->execute([$id]);

    return $stmt->fetch() ?: null;
}



}
