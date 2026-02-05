<?php

class DashboardController extends Controller {
public function index()
{
    if (Auth::isMaster()) {
        $masterId = Auth::user()['id'];

        $stats  = MasterDashboard::stats($masterId);
        $orders = MasterDashboard::activeOrders($masterId);
        $stuck  = MasterDashboard::stuckOrders($masterId);

        $this->view('dashboard_master', [
            'stats'  => $stats,
            'orders' => $orders,
            'stuck'  => $stuck
        ]);
        return;
    }

    // admin
    $stats   = Dashboard::stats();
    $masters = Dashboard::mastersLoad();
    $stuck   = Dashboard::stuckOrders();

    $this->view('dashboard', [
        'stats'   => $stats,
        'masters' => $masters,
        'stuck'   => $stuck
    ]);
}


}