<div class="container mt-4">
  <h3>Редактирование мастера</h3>

  <form method="post" class="card p-3">
<?= Csrf::inputField(); ?>
    <div class="row g-3">

      <div class="col-md-4">
        <label class="form-label">Имя</label>
        <input name="name" class="form-control"
               value="<?= htmlspecialchars($master['name']) ?>" required>
      </div>

      <div class="col-md-4">
        <label class="form-label">Email</label>
        <input name="email" type="email" class="form-control"
               value="<?= htmlspecialchars($master['email']) ?>" required>
      </div>

      <div class="col-md-4">
        <label class="form-label">% зарплаты</label>
        <input name="salary_percent" type="number" step="0.01"
               class="form-control"
               value="<?= $master['salary_percent'] ?>">
      </div>

      <div class="col-md-4">
        <label class="form-label">Новый пароль (если нужно)</label>
        <input name="password" type="password" class="form-control">
      </div>

    </div>

    <div class="mt-3 d-flex gap-2">
      <button class="btn btn-success">
        💾 Сохранить
      </button>
      <a href="<?= BASE_URL ?>/masters" class="btn btn-outline-secondary">
        Отмена
      </a>
    </div>

  </form>
</div>
