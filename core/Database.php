<?php

require_once __DIR__ . '/../config/app.php';

class Database {
  private static $instance = null;
  private $pdo;

  private function __construct() {
    $this->pdo = new PDO(
      DB_DSN,
      DB_USER,
      DB_PASS,
      [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
      ]
    );
  }

  public static function getInstance() {
    if (self::$instance === null) {
      self::$instance = new Database();
    }
    return self::$instance->pdo;
  }
}
