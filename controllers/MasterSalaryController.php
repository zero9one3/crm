<?php

class MasterSalaryController extends Controller
{
    public function index()
    {
        $masterId = Auth::user()['id'];

        $from = $_GET['from'] ?? date('Y-m-01');
        $to   = $_GET['to'] ?? date('Y-m-d');

        $summary = MasterSalary::summary($masterId, $from, $to);
        $orders  = MasterSalary::details($masterId, $from, $to);

        $this->view('salary/master', [
            'summary' => $summary,
            'orders'  => $orders,
            'from'    => $from,
            'to'      => $to
        ]);
    }
}
