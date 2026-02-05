<?php
$history = $history ?? [];
$statusMap = [
    'new' => 'Новый',
    'in_progress' => 'В работе',
    'done' => 'Готов',
    'issued' => 'Выдан'
];
function masterName($id) {
    static $cache = [];

    if (!$id) return '—';

    if (!isset($cache[$id])) {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("SELECT name FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $cache[$id] = $stmt->fetchColumn() ?: '—';
    }

    return $cache[$id];
}

?>

<div class="container mt-4">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Заказ №<?= $order['id'] ?></h3>
    <a href="<?= BASE_URL ?>/orders" class="btn btn-outline-secondary btn-sm">
      ← К списку
    </a>
  </div>

  <!-- ===== ФОРМА ЗАКАЗА ===== -->
  <form method="post" class="card p-3 mb-4">
<?= Csrf::inputField(); ?>
    <div class="row g-3">

      <div class="col-md-4">
        <label class="form-label">Мастер</label>
        <select name="master_id" class="form-select">
          <?php foreach ($masters as $m): ?>
            <option value="<?= $m['id'] ?>"
              <?= $order['master_id'] == $m['id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($m['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-md-4">
        <label class="form-label">Статус</label>
<?php if ($order['status'] === 'issued'): ?>

  <input class="form-control" disabled
         value="<?= $statusMap[$order['status']] ?>">

  <input type="hidden" name="status" value="issued">

<?php else: ?>

  <select name="status" class="form-select">
    <?php foreach ($statusMap as $key => $label): ?>
      <option value="<?= $key ?>"
        <?= $order['status'] === $key ? 'selected' : '' ?>>
        <?= $label ?>
      </option>
    <?php endforeach; ?>
  </select>

<?php endif; ?>

      </div>

      <div class="col-md-4">
        <label class="form-label">Дата создания</label>
        <input class="form-control" disabled
               value="<?= $order['created_at'] ?>">
      </div>

      <div class="col-md-6">
        <label class="form-label">Устройство</label>
        <input name="device" class="form-control"
               value="<?= htmlspecialchars($order['device']) ?>">
      </div>

      <div class="col-md-6">
        <label class="form-label">Проблема</label>
        <input name="problem" class="form-control"
               value="<?= htmlspecialchars($order['problem']) ?>">
      </div>

      <div class="col-md-3">
        <label class="form-label">Цена</label>
<input name="price" type="number" step="0.01"
       class="form-control"
       value="<?= $order['price'] ?>"
       <?= $order['status'] === 'issued' ? 'readonly' : '' ?>>



      </div>

      <div class="col-md-3">
        <label class="form-label">Себестоимость</label>
<input name="cost" type="number" step="0.01"
       class="form-control"
       value="<?= $order['cost'] ?>"
       <?= $order['status'] === 'issued' ? 'readonly' : '' ?>>
      </div>

    </div>

    <div class="mt-3 d-flex gap-2">
<?php if ($order['status'] !== 'issued'): ?>

  <button class="btn btn-success">
    💾 Сохранить изменения
  </button>
<a
  href="<?= BASE_URL ?>/orders/receipt?id=<?= $order['id'] ?>"
  target="_blank"
  class="btn btn-outline-secondary">
  🖨 Печать квитанции
</a>

<a
  href="<?= BASE_URL ?>/orders"
  class="btn btn-outline-primary">
  ← К списку заказов
</a>


  <?php if (Auth::isAdmin()): ?>
    <form method="post"
          action="<?= BASE_URL ?>/orders/delete"
          onsubmit="return confirm('Удалить заказ?')">
      <input type="hidden" name="id" value="<?= $order['id'] ?>">
      <button class="btn btn-danger">
        🗑 Удалить
      </button>
    </form>
  <?php endif; ?>

<?php endif; ?>

    </div>

  </form>
<?php if ($order['status'] === 'issued'): ?>
  <div class="alert alert-warning mt-3">
    Заказ выдан. Финансовые данные и статус заблокированы.
  </div>
<?php endif; ?>

  <!-- ===== ИСТОРИЯ ИЗМЕНЕНИЙ ===== -->
  <h5 class="mb-2">История изменений</h5>

  <table class="table table-sm table-bordered">
    <thead>
      <tr>
        <th>Дата</th>
        <th>Пользователь</th>
        <th>Изменение</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($history as $h): ?>
  <tr>
    <td><?= date('d.m.Y H:i', strtotime($h['created_at'])) ?></td>
    <td><?= htmlspecialchars($h['user_name']) ?></td>
    <td>
      <?php if ($h['field'] === 'master_id'): ?>
        Мастер изменён: <?= masterName($h['old_value']) ?> → <?= masterName($h['new_value']) ?>

      <?php elseif ($h['field'] === 'status'): ?>
        Статус: <?= $statusMap[$h['old_value']] ?? $h['old_value'] ?> → <?= $statusMap[$h['new_value']] ?? $h['new_value'] ?>

      <?php elseif ($h['field'] === 'price'): ?>
        Цена: <?= $h['old_value'] ?> → <?= $h['new_value'] ?> ₽

      <?php endif; ?>
    </td>
  </tr>
<?php endforeach; ?>

      

      <?php if (!$history): ?>
        <tr>
          <td colspan="3" class="text-center text-muted">
            История изменений пуста
          </td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>

</div>

