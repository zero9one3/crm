<div class="container mt-4">
  <h3>Новый заказ</h3>

  <form method="post" action="<?= BASE_URL ?>/orders/create" class="row g-3">

    <div class="col-md-4">
      <label class="form-label">Имя клиента</label>
      <input name="client_name" class="form-control">
    </div>

    <div class="col-md-4">
      <label class="form-label">Телефон *</label>
      <input name="client_phone" required class="form-control">
    </div>

    <div class="col-md-4">
      <label class="form-label">Мастер</label>
      <select name="master_id" class="form-select" required>
        <?php foreach ($masters as $m): ?>
          <option value="<?= $m['id'] ?>">
            <?= htmlspecialchars($m['name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="col-md-6">
      <label class="form-label">Устройство</label>
      <input name="device" class="form-control">
    </div>

    <div class="col-md-6">
      <label class="form-label">Проблема</label>
      <input name="problem" class="form-control">
    </div>

    <div class="col-md-3">
      <label class="form-label">Цена</label>
      <input name="price" type="number" step="0.01" class="form-control">
    </div>

    <div class="col-md-3">
      <label class="form-label">Себестоимость</label>
      <input name="cost" type="number" step="0.01" class="form-control">
    </div>
<?php $conditions = require __DIR__ . '/../../config/device_conditions.php'; ?>

<div class="card p-3 mb-3">
  <h5>Внешнее состояние устройства</h5>

  <div class="row">
    <?php foreach ($conditions as $key => $label): ?>
      <div class="col-md-4">
        <div class="form-check">
          <input class="form-check-input"
                 type="checkbox"
                 name="condition_state[<?= $key ?>]"
                 value="1"
                 id="cond_<?= $key ?>">
          <label class="form-check-label" for="cond_<?= $key ?>">
            <?= $label ?>
          </label>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

    <div class="col-md-12">
<button type="submit" class="btn btn-primary" id="createBtn">
  Создать заказ
</button>

<script>
document.querySelector('form').addEventListener('submit', function () {
  const btn = document.getElementById('createBtn');
  btn.disabled = true;
  btn.innerText = 'Создание...';
});
</script>


    </div>

  </form>
</div>
