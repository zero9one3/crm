<div class="container mt-4">
  <h3>Зарплата мастеров</h3>

  <form method="get" class="row g-2 mb-3">
    <div class="col-md-3">
      <input type="date" name="from"
             value="<?= $dateFrom ?>" class="form-control">
    </div>
    <div class="col-md-3">
      <input type="date" name="to"
             value="<?= $dateTo ?>" class="form-control">
    </div>
    <div class="col-md-2">
      <button class="btn btn-primary">Показать</button>
    </div>
  </form>

  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Мастер</th>
        <th>Заказов</th>
        <th>Прибыль</th>
        <th>%</th>
        <th>Зарплата</th>
        <th>Детализация</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($data as $row): ?>
        <tr>
          <td><?= htmlspecialchars($row['master_name']) ?></td>
          <td><?= $row['orders_count'] ?></td>
          <td><?= number_format($row['total_profit'], 2, '.', ' ') ?> ₽</td>
          <td><?= $row['salary_percent'] ?>%</td>
          <td>
            <b><?= number_format($row['salary'], 2, '.', ' ') ?> ₽</b>
          </td>
          <td>
 <?php
$query = http_build_query([
    'master_id' => $row['master_id'],
    'from' => $dateFrom,
    'to' => $dateTo
]);
?>

<a href="<?= BASE_URL ?>/salary/details?<?= $query ?>">
  Подробнее
</a>

</td>

        </tr>
      <?php endforeach; ?>

      <?php if (!$data): ?>
        <tr>
          <td colspan="5" class="text-center text-muted">
            Нет данных за период
          </td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
