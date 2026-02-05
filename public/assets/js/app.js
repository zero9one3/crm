$(document).on('click', '.js-delete', function (e) {
    e.preventDefault();

    const btn = $(this);

    deleteAction = {
        id: btn.data('id'),
        url: btn.data('url'),
        row: btn.closest('tr')
    };

    $('#confirmModalText').text('Удалить? Действие необратимо.');
    const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
    modal.show();
});

$('#confirmModalYes').on('click', function () {
    if (!deleteAction) return;

    $.ajax({
        url: deleteAction.url,
        type: 'POST',
        dataType: 'json',
        data: { id: deleteAction.id },
        success: function (res) {
            const confirmModal = bootstrap.Modal.getInstance(
                document.getElementById('confirmModal')
            );
            confirmModal.hide();

            if (res.status === 'ok') {
                deleteAction.row.fadeOut(200, function () {
                    $(this).remove();
                });
            } else {
                showAlert(res.message || 'Ошибка');
            }
        },
        error: function () {
            showAlert('Ошибка соединения с сервером');
        }
    });

    function showAlert(text) {
    $('#alertModalText').text(text);
    const modal = new bootstrap.Modal(document.getElementById('alertModal'));
    modal.show();
}

});
