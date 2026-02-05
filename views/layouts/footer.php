<!-- ===== MODAL CONFIRM ===== -->
<div class="modal fade" id="confirmModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Подтверждение</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <p id="confirmModalText">Вы уверены?</p>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Отмена
        </button>
        <button type="button" class="btn btn-danger" id="confirmModalYes">
          Удалить
        </button>
      </div>

    </div>
  </div>
</div>

<!-- ===== MODAL ALERT ===== -->
<div class="modal fade" id="alertModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Сообщение</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body" id="alertModalText">
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
          ОК
        </button>
      </div>

    </div>
  </div>
</div>


<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="<?= BASE_URL ?>/assets/js/bootstrap.bundle.min.js"></script>
<script src="<?= BASE_URL ?>/assets/js/app.js"></script>
</body>
</html>
