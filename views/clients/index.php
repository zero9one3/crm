<form class="mb-3">
  <input name="q" value="<?= htmlspecialchars($search) ?>"
         class="form-control"
         placeholder="Поиск по имени или телефону">
</form>

<table class="table table-sm">
  <tr>
    <th>Имя</th>
    <th>Телефон</th>
    <th>Заказов</th>
    <th></th>
  </tr>

  <?php foreach ($clients as $c): ?>
  <tr>
    <td><?= htmlspecialchars($c['name']) ?></td>
    <td><?= htmlspecialchars($c['phone']) ?></td>
    <td><?= $c['orders_count'] ?></td>
    <td>
      <a href="<?= BASE_URL ?>/clients/view?id=<?= $c['id'] ?>"
         class="btn btn-sm btn-outline-primary">
        Открыть
      </a>
    </td>
  </tr>
  <?php endforeach; ?>
</table>
