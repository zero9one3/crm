<?php

class MasterDashboard
{
    public static function stats(int $masterId): array
    {
        $pdo = Database::getInstance();

        return [
            'new' => self::countByStatus($masterId, 'new'),
            'in_progress' => self::countByStatus($masterId, 'in_progress'),
            'done' => self::countByStatus($masterId, 'done'),
        ];
    }

    private static function countByStatus(int $masterId, string $status): int
    {
        $pdo = Database::getInstance();

        $stmt = $pdo->prepare(
            "SELECT COUNT(*) 
             FROM orders 
             WHERE master_id = ? AND status = ?"
        );
        $stmt->execute([$masterId, $status]);

        return (int)$stmt->fetchColumn();
    }

    public static function activeOrders(int $masterId): array
    {
        $pdo = Database::getInstance();

        $stmt = $pdo->prepare(
            "SELECT id, device, status, created_at
             FROM orders
             WHERE master_id = ?
               AND status IN ('new','in_progress')
             ORDER BY created_at ASC"
        );
        $stmt->execute([$masterId]);

        return $stmt->fetchAll();
    }

    public static function stuckOrders(int $masterId): array
    {
        $pdo = Database::getInstance();

        $stmt = $pdo->prepare(
            "SELECT id, device, created_at
             FROM orders
             WHERE master_id = ?
               AND status = 'in_progress'
               AND created_at < NOW() - INTERVAL 3 DAY"
        );
        $stmt->execute([$masterId]);

        return $stmt->fetchAll();
    }
}
