<?php
namespace App\Controller;

use App\Report\MonthlyReport;
use Cake\I18n\FrozenDate;

class ReportsController extends AppController
{
    public function monthly()
    {
        $month = $this->request->getQuery('month', FrozenDate::today()->format('Y-m'));

        $report = new MonthlyReport($this->getTableLocator()->get('Loans'), $month);
        $report->build();

        $this->set(compact('report', 'month'));
    }
}
