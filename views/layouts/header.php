<!doctype html>
<html lang="ru_RU">
<head>
<meta charset="utf-8">
<link href="<?= BASE_URL ?>/assets/css/bootstrap.min.css" rel="stylesheet">
<title>CRM</title>
</head>
<body>
<nav class="navbar navbar-dark bg-dark px-3">
  <a class="navbar-brand" href="<?= BASE_URL ?>/"><span class="navbar-brand">
  <?= Auth::isMaster() ? 'Мой кабинет Service CRM' : 'Service CRM' ?>
</span>
</a>
  

  <div class="d-flex gap-2">
    <a href="<?= BASE_URL ?>/orders" class="btn btn-sm btn-outline-light">
      Заказы
    </a>

    <?php if (Auth::isAdmin()): ?>
      <a href="<?= BASE_URL ?>/orders/create" class="btn btn-sm btn-outline-light">
        + Заказ
      </a>
    <?php endif; ?>
    <a href="<?= BASE_URL ?>/clients" class="btn btn-sm btn-outline-light">
      Клиенты
    </a>
    <?php if (Auth::isAdmin()): ?>
  <a href="<?= BASE_URL ?>/masters" class="btn btn-sm btn-outline-light">
    Мастера
  </a>
<?php endif; ?>
    <?php if (Auth::isAdmin()): ?>
  <a href="<?= BASE_URL ?>/salary" class="btn btn-sm btn-outline-light">
    Зарплаты
  </a>
<?php endif; ?>
<?php if (Auth::isMaster()): ?>
  <a href="<?= BASE_URL ?>/my-salary" class="btn btn-sm btn-outline-light">
    Моя зарплата
  </a>
<?php endif; ?>

    <a href="<?= BASE_URL ?>/logout" class="btn btn-sm btn-outline-warning">
      Выход
    </a>
  </div>
</nav>

