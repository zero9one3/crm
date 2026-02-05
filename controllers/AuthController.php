<?php
class AuthController {
  public function login() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $pdo = Database::getInstance();
      $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
      $stmt->execute([$_POST['email']]);
      $user = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($user && password_verify($_POST['password'], $user['password'])) {
        Auth::login($user);
        header('Location: ' . BASE_URL . '/');
        exit;
      }
    }
    require 'views/auth/login.php';
  }

  public function logout() {
    Auth::logout();
    header('Location: ' . BASE_URL . '/login');
  }
}