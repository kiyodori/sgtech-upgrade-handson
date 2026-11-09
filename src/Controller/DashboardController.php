<?php
namespace App\Controller;

use Cake\Cache\Cache;
use Cake\I18n\FrozenDate;

class DashboardController extends AppController
{
    public function index()
    {
        $Loans = $this->getTableLocator()->get('Loans');
        $today = FrozenDate::today();

        // 件数の集計は重いので10分キャッシュする
        $counts = Cache::read('dashboard_counts');
        if ($counts === null) {
            $counts = [
                'loaned' => $Loans->find()
                    ->where(['Loans.returned_on IS' => null])
                    ->count(),
                'overdue' => $Loans->find()
                    ->where(['Loans.returned_on IS' => null, 'Loans.due_on <' => $today])
                    ->count(),
            ];
            Cache::write('dashboard_counts', $counts);
        }

        $overdueLoans = $Loans->find()
            ->contain(['Items', 'Users'])
            ->where(['Loans.returned_on IS' => null, 'Loans.due_on <' => $today])
            ->order(['Loans.due_on' => 'ASC'])
            ->all();

        $this->set(compact('counts', 'overdueLoans'));
    }
}
