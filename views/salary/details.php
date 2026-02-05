<div class="container mt-4">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>
      Зарплата мастера:
      <?= htmlspecialchars($master['name']) ?>
    </h3>

    <a href="<?= BASE_URL ?>/salary?from=<?= $dateFrom ?>&to=<?= $dateTo ?>"
       class="btn btn-outline-secondary btn-sm">
      ← Назад к отчёту
    </a>
  </div>

  <p class="text-muted">
    Период: <?= $dateFrom ?> — <?= $dateTo ?> |
    Процент: <?= $master['salary_percent'] ?>%
  </p>

  <table class="table table-bordered table-sm">
    <thead>
      <tr>
        <th>Заказ</th>
        <th>Устройство</th>
        <th>Цена</th>
        <th>Себестоимость</th>
        <th>Прибыль</th>
        <th>Начислено</th>
        <th>Дата</th>
      </tr>
    </thead>
    <tbody>

      <?php
        $totalProfit = 0;
        $totalSalary = 0;
      ?>

      <?php foreach ($orders as $o): ?>
        <?php
          $totalProfit += $o['profit'];
          $totalSalary += $o['salary'];
        ?>
        <tr>
          <td>#<?= $o['order_id'] ?></td>
          <td><?= htmlspecialchars($o['device']) ?></td>
          <td><?= number_format($o['price'], 2, '.', ' ') ?> ₽</td>
          <td><?= number_format($o['cost'], 2, '.', ' ') ?> ₽</td>
          <td><?= number_format($o['profit'], 2, '.', ' ') ?> ₽</td>
          <td><b><?= number_format($o['salary'], 2, '.', ' ') ?> ₽</b></td>
          <td><?= $o['created_at'] ?></td>
        </tr>
      <?php endforeach; ?>

      <?php if (!$orders): ?>
        <tr>
          <td colspan="7" class="text-center text-muted">
            Заказов за период нет
          </td>
        </tr>
      <?php endif; ?>

    </tbody>

    <?php if ($orders): ?>
      <tfoot>
        <tr>
          <th colspan="4" class="text-end">Итого:</th>
          <th><?= number_format($totalProfit, 2, '.', ' ') ?> ₽</th>
          <th><?= number_format($totalSalary, 2, '.', ' ') ?> ₽</th>
          <th></th>
        </tr>
      </tfoot>
    <?php endif; ?>
  </table>

</div>
