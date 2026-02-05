<?php

class OrderHistory
{
    public static function add(
        int $orderId,
        string $field,
        ?string $old,
        ?string $new
    ): void {
        $pdo = Database::getInstance();

        $stmt = $pdo->prepare(
            "INSERT INTO order_history
             (order_id, user_id, field, old_value, new_value)
             VALUES (?, ?, ?, ?, ?)"
        );

        $stmt->execute([
            $orderId,
            Auth::user()['id'],
            $field,
            $old,
            $new
        ]);
    }

    public static function forOrder(int $orderId): array
    {
        $pdo = Database::getInstance();

        $stmt = $pdo->prepare(
            "SELECT h.*, u.name AS user_name
             FROM order_history h
             JOIN users u ON u.id = h.user_id
             WHERE h.order_id = ?
             ORDER BY h.created_at DESC"
        );

        $stmt->execute([$orderId]);
        return $stmt->fetchAll();
    }
    public static function getByOrder(int $orderId): array
{
    $pdo = Database::getInstance();

    $stmt = $pdo->prepare(
        "SELECT h.*, u.name AS user_name
         FROM order_history h
         LEFT JOIN users u ON u.id = h.user_id
         WHERE h.order_id = ?
         ORDER BY h.created_at DESC"
    );

    $stmt->execute([$orderId]);

    return $stmt->fetchAll() ?: [];
}

}
