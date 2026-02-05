<!doctype html>
<html lang="ru">
<head>
<meta charset="utf-8">
<title>Квитанция приёма №<?= $order['id'] ?></title>

<style>
@page {
  size: A4;
  margin: 15mm;
}

body {
  font-family: Arial, sans-serif;
  font-size: 12px;
  color: #000;
}

h1, h2 {
  margin: 0 0 5px 0;
}

.section {
  border: 1px solid #000;
  padding: 10px;
  margin-bottom: 10px;
}

.row {
  display: flex;
  justify-content: space-between;
  margin-bottom: 5px;
}

.col {
  width: 48%;
}

.label {
  font-weight: bold;
}

.small {
  font-size: 11px;
}

.conditions {
  font-size: 11px;
  line-height: 1.4;
}

.signature {
  margin-top: 25px;
  display: flex;
  justify-content: space-between;
}

.signature div {
  width: 45%;
  text-align: center;
}

.signature span {
  display: block;
  margin-top: 30px;
  border-top: 1px solid #000;
}

.cut {
  border-top: 2px dashed #000;
  margin: 20px 0;
  text-align: center;
  font-size: 11px;
}

@media print {
  button { display: none; }
}
</style>
</head>

<body>

<!-- ===== ЧАСТЬ СЕРВИСА ===== -->
<div class="section">

  <h1>Квитанция приёма в ремонт №<?= $order['id'] ?></h1>
  <div class="small">
    Дата приёма: <?= date('d.m.Y H:i', strtotime($order['created_at'])) ?>
  </div>

  <hr>

  <div class="row">
    <div class="col">
      <div><span class="label">Клиент:</span> <?= htmlspecialchars($order['client_name']) ?></div>
      <div><span class="label">Телефон:</span> <?= htmlspecialchars($order['client_phone']) ?></div>
    </div>
    <div class="col">
      <div><span class="label">Устройство:</span> <?= htmlspecialchars($order['device']) ?></div>
      <div><span class="label">Мастер:</span> <?= htmlspecialchars($order['master_name'] ?? '—') ?></div>
    </div>
  </div>

  <hr>

  <div>
    <span class="label">Заявленная неисправность:</span><br>
    <?= nl2br(htmlspecialchars($order['problem'])) ?>
  </div>

  <hr>

  <div class="small">
    Клиент подтверждает согласие на проведение диагностики и ремонта.
  </div>

  <div class="signature">
    <div>
      Клиент
      <span>Подпись</span>
    </div>
    <div>
      Сервисный центр
      <span>Подпись</span>
    </div>
  </div>

</div>

<div class="cut">— отрывная часть для клиента —</div>

<!-- ===== ЧАСТЬ КЛИЕНТА ===== -->
<div class="section">

  <h2>Квитанция клиента №<?= $order['id'] ?></h2>
  <div class="small">
    Дата приёма: <?= date('d.m.Y H:i', strtotime($order['created_at'])) ?>
  </div>

  <hr>

  <div>
    <div><span class="label">Клиент:</span> <?= htmlspecialchars($order['client_name']) ?></div>
    <div><span class="label">Телефон:</span> <?= htmlspecialchars($order['client_phone']) ?></div>
    <div><span class="label">Устройство:</span> <?= htmlspecialchars($order['device']) ?></div>
    <div><span class="label">Заказ №:</span> <?= $order['id'] ?></div>
  </div>

  <hr>

  <div class="conditions">
    <span class="label">Условия ремонта и гарантии:</span><br><br>

    1. Сервисный центр не несёт ответственности за скрытые дефекты,
    выявленные в процессе ремонта.<br>
    2. Диагностика может быть платной в случае отказа от ремонта.<br>
    3. Гарантия распространяется только на выполненные работы и
    установленные запчасти.<br>
    4. Срок гарантии составляет 30 дней, если иное не указано отдельно.<br>
    5. Клиент подтверждает, что ознакомлен с условиями ремонта и согласен с ними.<br>
  </div>
<?php
$conditionsList = require __DIR__ . '/../../config/device_conditions.php';
$state = json_decode($order['condition_state'] ?? '{}', true);
?>

<hr>

<div>
  <span class="label">Внешнее состояние устройства:</span>

  <table style="width:100%; margin-top:5px; border-collapse:collapse;">
    <?php foreach ($conditionsList as $key => $label): ?>
      <tr>
        <td style="border:1px solid #000; padding:4px;">
          <?= $label ?>
        </td>
        <td style="border:1px solid #000; width:30px; text-align:center;">
          <?= !empty($state[$key]) ? '✔' : '' ?>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
</div>

  <div class="signature">
    <div>
      Клиент
      <span>Подпись</span>
    </div>
    <div>
      Сервисный центр
      <span>Подпись</span>
    </div>
  </div>

</div>

<br>

<button onclick="window.print()">Печать</button>

</body>
</html>
