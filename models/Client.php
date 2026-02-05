<?php

class Client
{
    public static function findOrCreate(string $name, string $phone): int
    {
        $pdo = Database::getInstance();

        // нормализуем телефон (минимум для MVP)
        $phone = preg_replace('/\D+/', '', $phone);

        $stmt = $pdo->prepare("SELECT id FROM clients WHERE phone = ?");
        $stmt->execute([$phone]);
        $client = $stmt->fetch();

        if ($client) {
            return (int)$client['id'];
        }

        $stmt = $pdo->prepare(
            "INSERT INTO clients (name, phone) VALUES (?, ?)"
        );
        $stmt->execute([$name ?: 'Без имени', $phone]);

        return (int)$pdo->lastInsertId();
    }
}
