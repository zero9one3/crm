<div class="container mt-4">
  <h3>Добавить мастера</h3>

  <form method="post" class="row g-3">
<?= Csrf::inputField(); ?>
    <div class="col-md-4">
      <label class="form-label">Имя</label>
      <input name="name" class="form-control" required>
    </div>

    <div class="col-md-4">
      <label class="form-label">Email (логин)</label>
      <input name="email" type="email" class="form-control" required>
    </div>

    <div class="col-md-4">
      <label class="form-label">Пароль</label>
      <input name="password" type="password" class="form-control" required>
    </div>

    <div class="col-md-12">
      <button class="btn btn-success">Создать</button>
    </div>

  </form>
</div>
