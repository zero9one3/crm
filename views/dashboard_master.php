<div class="container mt-4">

  <h3 class="mb-3">Мой дашборд</h3>

  <!-- ===== СТАТУСЫ ===== -->
  <div class="row g-3 mb-4">

    <div class="col-md-4">
      <div class="card p-3">
        <div class="text-muted">Новые</div>
        <h4><?= $stats['new'] ?></h4>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card p-3">
        <div class="text-muted">В работе</div>
        <h4><?= $stats['in_progress'] ?></h4>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card p-3">
        <div class="text-muted">Готовы</div>
        <h4><?= $stats['done'] ?></h4>
      </div>
    </div>

  </div>

  <!-- ===== МОИ ЗАКАЗЫ ===== -->
  <div class="card p-3 mb-4">
    <h5>Мои активные заказы</h5>

    <table class="table table-sm">
      <thead>
        <tr>
          <th>#</th>
          <th>Устройство</th>
          <th>Статус</th>
          <th>С</th>
          <th></th>
        </tr>
      </thead>
      <tbody>

        <?php foreach ($orders as $o): ?>
          <tr>
            <td><?= $o['id'] ?></td>
            <td><?= htmlspecialchars($o['device']) ?></td>
            <td><?= $o['status'] ?></td>
            <td><?= date('d.m', strtotime($o['created_at'])) ?></td>
            <td>
              <a href="<?= BASE_URL ?>/orders/edit?id=<?= $o['id'] ?>">
                Открыть
              </a>
            </td>
          </tr>
        <?php endforeach; ?>

        <?php if (!$orders): ?>
          <tr>
            <td colspan="5" class="text-center text-muted">
              Активных заказов нет
            </td>
          </tr>
        <?php endif; ?>

      </tbody>
    </table>
  </div>

  <!-- ===== ЗАВИСШИЕ ===== -->
  <?php if ($stuck): ?>
    <div class="card p-3 border-warning">
      <h5 class="text-warning">⚠ Зависшие заказы</h5>

      <ul class="mb-0">
        <?php foreach ($stuck as $o): ?>
          <li>
            #<?= $o['id'] ?> — <?= htmlspecialchars($o['device']) ?>
            (с <?= date('d.m', strtotime($o['created_at'])) ?>)
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

</div>
