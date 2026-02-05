<?php

class MasterSalary
{
    public static function summary(int $masterId, string $from, string $to): array
    {
        $pdo = Database::getInstance();

        $stmt = $pdo->prepare(
            "SELECT
                COUNT(o.id) AS orders_count,
                SUM(o.price - o.cost) AS profit,
                SUM((o.price - o.cost) * u.salary_percent / 100) AS salary
             FROM orders o
             JOIN users u ON u.id = o.issued_master_id
             WHERE o.status = 'issued'
               AND o.issued_master_id = ?
               AND o.created_at BETWEEN ? AND ?"
        );

        $stmt->execute([
            $masterId,
            $from . ' 00:00:00',
            $to . ' 23:59:59'
        ]);

        return $stmt->fetch() ?: [
            'orders_count' => 0,
            'profit' => 0,
            'salary' => 0
        ];
    }

    public static function details(int $masterId, string $from, string $to): array
    {
        $pdo = Database::getInstance();

        $stmt = $pdo->prepare(
            "SELECT
                o.id,
                o.device,
                o.price,
                o.cost,
                (o.price - o.cost) AS profit,
                u.salary_percent,
                ((o.price - o.cost) * u.salary_percent / 100) AS salary,
                o.created_at
             FROM orders o
             JOIN users u ON u.id = o.issued_master_id
             WHERE o.status = 'issued'
               AND o.issued_master_id = ?
               AND o.created_at BETWEEN ? AND ?
             ORDER BY o.created_at DESC"
        );

        $stmt->execute([
            $masterId,
            $from . ' 00:00:00',
            $to . ' 23:59:59'
        ]);

        return $stmt->fetchAll();
    }
}
