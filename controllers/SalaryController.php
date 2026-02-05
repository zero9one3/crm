<?php

class SalaryController extends Controller
{
    public function index()
    {
        $dateFrom = $_GET['from'] ?? date('Y-m-01');
        $dateTo   = $_GET['to'] ?? date('Y-m-d');

        $data = Salary::forPeriod($dateFrom, $dateTo);

        $this->view('salary/index', [
            'data'     => $data,
            'dateFrom' => $dateFrom,
            'dateTo'   => $dateTo
        ]);
    }
    public function details()
{
    $masterId = (int)($_GET['master_id'] ?? 0);

    if ($masterId <= 0) {
        http_response_code(404);
        echo 'Мастер не найден';
        return;
    }

    $dateFrom = $_GET['from'] ?? date('Y-m-01');
    $dateTo   = $_GET['to'] ?? date('Y-m-d');

    $pdo = Database::getInstance();

    $master = $pdo->prepare(
        "SELECT id, name, salary_percent
         FROM users
         WHERE id = ? AND role = 'master'"
    );
    $master->execute([$masterId]);
    $master = $master->fetch();

    if (!$master) {
        http_response_code(404);
        echo 'Мастер не найден';
        return;
    }

    $orders = Salary::detailsForMaster(
        $masterId,
        $dateFrom,
        $dateTo
    );

    $this->view('salary/details', [
        'master'   => $master,
        'orders'   => $orders,
        'dateFrom' => $dateFrom,
        'dateTo'   => $dateTo
    ]);
}

}
