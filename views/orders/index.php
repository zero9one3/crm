<?php
$statusMap = [
  'new' => 'Новый',
  'in_progress' => 'В работе',
  'done' => 'Готов',
  'issued' => 'Выдан'
];
?>


<div class="container mt-4">
  <h3>Заказы</h3>

  <form method="get" class="row g-2 mb-3">
    <div class="col-md-2">
      <select name="status" class="form-select">
        <option value="">Все статусы</option>
        <?php foreach (['new','in_progress','done','issued'] as $s): ?>
          <option value="<?= $s ?>"
            <?= $filters['status'] === $s ? 'selected' : '' ?>>
            <?= $s ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="col-md-2">
      <input type="date" name="date_from"
             value="<?= htmlspecialchars($filters['date_from'] ?? '') ?>"
             class="form-control">
    </div>

    <div class="col-md-2">
      <input type="date" name="date_to"
             value="<?= htmlspecialchars($filters['date_to'] ?? '') ?>"
             class="form-control">
    </div>

    <div class="col-md-3">
      <input type="text" name="client"
             placeholder="Клиент / телефон"
             value="<?= htmlspecialchars($filters['client'] ?? '') ?>"
             class="form-control">
    </div>

    <div class="col-md-2">
      <button class="btn btn-primary w-100">Фильтр</button>
    </div>
  </form>

  <table class="table table-bordered table-sm">
    <thead>
      <tr>
        <th>ID</th>
        <th>Клиент</th>
        <th>Устройство</th>
        <th>Статус</th>
        <th>Дата</th>
<th>Действие</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($orders as $order): ?>
        <tr>
          <td><?= $order['id'] ?></td>
          <td><?= htmlspecialchars($order['client_name']) ?></td>
          <td><?= htmlspecialchars($order['device']) ?></td>
          <td><?= $statusMap[$order['status']] ?? $order['status'] ?></td>
          <td><?= $order['created_at'] ?></td>
<td class="d-flex gap-1">
  <a href="<?= BASE_URL ?>/orders/receipt?id=<?= $order['id'] ?>"
   target="_blank"
   class="btn btn-sm btn-outline-secondary">
  🖨
</a>

  <a href="<?= BASE_URL ?>/orders/edit?id=<?= $order['id'] ?>"
     class="btn btn-sm btn-primary">
    Открыть
  </a>

 <?php if (Auth::isAdmin()): ?>
  <button
    class="btn btn-sm btn-danger js-delete"
    data-id="<?= $order['id'] ?>"
    data-url="<?= BASE_URL ?>/orders/delete">
    🗑
  </button>
<?php endif; ?>

</td>

        </tr>
      <?php endforeach; ?>

      <?php if (!$orders): ?>
        <tr>
          <td colspan="5" class="text-center text-muted">
            Заказы не найдены
          </td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
