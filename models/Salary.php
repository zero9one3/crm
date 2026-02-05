<?php

class Salary
{
    public static function forPeriod(
        string $dateFrom,
        string $dateTo
    ): array {
        $pdo = Database::getInstance();

        $stmt = $pdo->prepare(
            "SELECT 
                u.id AS master_id,
                u.name AS master_name,
                u.salary_percent,
                COUNT(o.id) AS orders_count,
                SUM(o.price - o.cost) AS total_profit,
                SUM((o.price - o.cost) * u.salary_percent / 100) AS salary
             FROM orders o
             JOIN users u ON u.id = o.issued_master_id
             WHERE o.status = 'issued'
               AND o.created_at BETWEEN ? AND ?
             GROUP BY u.id
             ORDER BY salary DESC"
        );

        $stmt->execute([
            $dateFrom . ' 00:00:00',
            $dateTo . ' 23:59:59'
        ]);

        return $stmt->fetchAll();
    }
    
    public static function detailsForMaster(
    int $masterId,
    string $dateFrom,
    string $dateTo
): array {
    $pdo = Database::getInstance();

    $stmt = $pdo->prepare(
        "SELECT
            o.id AS order_id,
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
        $dateFrom . ' 00:00:00',
        $dateTo . ' 23:59:59'
    ]);

    return $stmt->fetchAll();
}

}
