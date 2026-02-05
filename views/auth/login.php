<!doctype html>
<html>
<head>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">
<form method="post" class="card p-4 shadow" style="width:300px">
  <?= Csrf::inputField(); ?>
  <h4>CRM Login</h4>
  <input name="email" class="form-control mb-2" placeholder="Email">
  <input type="password" name="password" class="form-control mb-2" placeholder="Password">
  <button class="btn btn-primary w-100">Login</button>
</form>
</body>
</html>