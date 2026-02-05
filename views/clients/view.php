<h3><?= htmlspecialchars($client['name']) ?></h3>
<p>Телефон: <?= htmlspecialchars($client['phone']) ?></p>

<a href="<?= BASE_URL ?>/clients/edit?id=<?= $client['id'] ?>"
   class="btn btn-sm btn-outline-secondary">
  Редактировать
</a>

<hr>

<h5>Заказы клиента</h5>

<table class="table table-sm">
  <tr>
    <th>#</th>
    <th>Устройство</th>
    <th>Статус</th>
    <th>Дата</th>
  </tr>

  <?php foreach ($orders as $o): ?>
  <tr>
    <td><?= $o['id'] ?></td>
    <td><?= htmlspecialchars($o['device']) ?></td>
    <td><?= $o['status'] ?></td>
    <td><?= date('d.m.Y', strtotime($o['created_at'])) ?></td>
  </tr>
  <?php endforeach; ?>
</table>
