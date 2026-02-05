<div class="container mt-4">
  <h3>Мастера</h3>

  <a href="<?= BASE_URL ?>/masters/create" class="btn btn-success mb-3">
    + Добавить мастера
  </a>

  <table class="table table-bordered">
    <tr>
      <th>ID</th>
      <th>Имя</th>
      <th>Email</th>
      <th>Действия</th>
    </tr>

 <?php foreach ($masters as $m): ?>
<tr>
  <td><?= $m['id'] ?></td>
  <td><?= htmlspecialchars($m['name']) ?></td>
  <td><?= htmlspecialchars($m['email']) ?></td>
  <td>
    <a href="<?= BASE_URL ?>/masters/edit?id=<?= $m['id'] ?>"
       class="btn btn-sm btn-primary">
       Редактировать
    </a>

    <?php if ($m['orders_count'] == 0): ?>
      <button
        class="btn btn-sm btn-danger js-delete"
        data-id="<?= $m['id'] ?>"
        data-url="<?= BASE_URL ?>/masters/delete">
        🗑
      </button>
    <?php else: ?>
      <span class="text-muted">Есть заказы</span>
    <?php endif; ?>
  </td>
</tr>
<?php endforeach; ?>

    <?php if (!$masters): ?>
      <tr>
        <td colspan="3" class="text-center text-muted">
          Мастеров пока нет
        </td>
      </tr>
    <?php endif; ?>
  </table>
</div>
