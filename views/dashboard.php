<div class="container mt-4">

  <h3 class="mb-3">Дашборд</h3>

  <!-- ===== КАРТОЧКИ ===== -->
  <div class="row g-3 mb-4">

    <div class="col-md-3">
      <div class="card p-3">
        <div class="text-muted">Всего заказов</div>
        <h4><?= $stats['orders_total'] ?></h4>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card p-3">
        <div class="text-muted">В работе</div>
        <h4><?= $stats['orders_in_progress'] ?></h4>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card p-3">
        <div class="text-muted">Готово</div>
        <h4><?= $stats['orders_done'] ?></h4>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card p-3 bg-light">
        <div class="text-muted">Прибыль сегодня</div>
        <h4><?= number_format($stats['profit_today'], 0, '.', ' ') ?> ₽</h4>
      </div>
    </div>

  </div>

  <!-- ===== ПРИБЫЛЬ ===== -->
  <div class="card p-3 mb-4">
    <strong>Прибыль за месяц:</strong>
    <?= number_format($stats['profit_month'], 0, '.', ' ') ?> ₽
  </div>

  <!-- ===== ЗАГРУЗКА МАСТЕРОВ ===== -->
  <div class="card p-3 mb-4">
    <h5>Загрузка мастеров</h5>
    <table class="table table-sm">
      <?php foreach ($masters as $m): ?>
        <tr>
          <td><?= htmlspecialchars($m['name']) ?></td>
          <td>
            <?= $m['active_orders'] ?> активных заказов
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
  </div>

  <!-- ===== ЗАВИСШИЕ ЗАКАЗЫ ===== -->
  <?php if ($stuck): ?>
    <div class="card p-3 border-warning">
      <h5 class="text-warning">⚠ Зависшие заказы</h5>

      <table class="table table-sm">
        <?php foreach ($stuck as $o): ?>
          <tr>
            <td>#<?= $o['id'] ?></td>
            <td><?= htmlspecialchars($o['device']) ?></td>
            <td><?= $o['created_at'] ?></td>
            <td>
              <a href="<?= BASE_URL ?>/orders/edit?id=<?= $o['id'] ?>">
                Открыть
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      </table>
    </div>
  <?php endif; ?>

</div>
