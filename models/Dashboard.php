<?php

class Dashboard
{
    public static function stats(): array
    {
        $pdo = Database::getInstance();

        return [
            'orders_total' => $pdo->query(
                "SELECT COUNT(*) FROM orders"
            )->fetchColumn(),

            'orders_in_progress' => $pdo->query(
                "SELECT COUNT(*) FROM orders WHERE status = 'in_progress'"
            )->fetchColumn(),

            'orders_done' => $pdo->query(
                "SELECT COUNT(*) FROM orders WHERE status = 'done'"
            )->fetchColumn(),

            'profit_today' => $pdo->query(
                "SELECT COALESCE(SUM(price - cost),0)
                 FROM orders
                 WHERE status = 'issued'
                   AND DATE(created_at) = CURDATE()"
            )->fetchColumn(),

            'profit_month' => $pdo->query(
                "SELECT COALESCE(SUM(price - cost),0)
                 FROM orders
                 WHERE status = 'issued'
                   AND created_at >= DATE_FORMAT(NOW(), '%Y-%m-01')"
            )->fetchColumn(),
        ];
    }

    public static function mastersLoad(): array
    {
        $pdo = Database::getInstance();

        return $pdo->query(
            "SELECT 
                u.name,
                COUNT(o.id) AS active_orders
             FROM users u
             LEFT JOIN orders o
               ON o.master_id = u.id
              AND o.status IN ('new','in_progress')
             WHERE u.role = 'master'
             GROUP BY u.id
             ORDER BY active_orders DESC"
        )->fetchAll();
    }

    public static function stuckOrders(): array
    {
        $pdo = Database::getInstance();

        return $pdo->query(
            "SELECT o.id, o.device, o.created_at
             FROM orders o
             WHERE o.status = 'in_progress'
               AND o.created_at < NOW() - INTERVAL 3 DAY
             ORDER BY o.created_at ASC
             LIMIT 5"
        )->fetchAll();
    }
}
