<div class="container mt-4">

  <h3 class="mb-3">Моя зарплата</h3>

  <!-- ===== ПЕРИОД ===== -->
  <form method="get" class="row g-2 mb-3">
    <div class="col-md-3">
      <input type="date" name="from" value="<?= $from ?>" class="form-control">
    </div>
    <div class="col-md-3">
      <input type="date" name="to" value="<?= $to ?>" class="form-control">
    </div>
    <div class="col-md-2">
      <button class="btn btn-primary">Показать</button>
    </div>
  </form>

  <!-- ===== ИТОГО ===== -->
  <div class="row g-3 mb-4">

    <div class="col-md-4">
      <div class="card p-3">
        <div class="text-muted">Выдано заказов</div>
        <h4><?= $summary['orders_count'] ?></h4>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card p-3">
        <div class="text-muted">Прибыль</div>
        <h4><?= number_format($summary['profit'], 0, '.', ' ') ?> ₽</h4>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card p-3 bg-light">
        <div class="text-muted">Моя зарплата</div>
        <h4><?= number_format($summary['salary'], 0, '.', ' ') ?> ₽</h4>
      </div>
    </div>

  </div>

  <!-- ===== ДЕТАЛИЗАЦИЯ ===== -->
  <div class="card p-3">
    <h5>По заказам</h5>

    <table class="table table-sm table-bordered">
      <thead>
        <tr>
          <th>#</th>
          <th>Устройство</th>
          <th>Цена</th>
          <th>Себестоимость</th>
          <th>Начислено</th>
          <th>Дата</th>
        </tr>
      </thead>
      <tbody>

        <?php foreach ($orders as $o): ?>
          <tr>
            <td><?= $o['id'] ?></td>
            <td><?= htmlspecialchars($o['device']) ?></td>
            <td><?= number_format($o['price'], 2, '.', ' ') ?> ₽</td>
            <td><?= number_format($o['cost'], 2, '.', ' ') ?> ₽</td>
            <td><b><?= number_format($o['salary'], 2, '.', ' ') ?> ₽</b></td>
            <td><?= date('d.m.Y', strtotime($o['created_at'])) ?></td>
          </tr>
        <?php endforeach; ?>

        <?php if (!$orders): ?>
          <tr>
            <td colspan="6" class="text-center text-muted">
              Заказов за период нет
            </td>
          </tr>
        <?php endif; ?>

      </tbody>
    </table>
  </div>

</div>
