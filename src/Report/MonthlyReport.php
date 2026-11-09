<?php
namespace App\Report;

use App\Model\Table\LoansTable;
use Cake\I18n\FrozenDate;

/**
 * 月次レポートの集計
 */
class MonthlyReport
{
    private $Loans;
    private $month;

    public function __construct(LoansTable $Loans, $month)
    {
        $this->Loans = $Loans;
        $this->month = $month;
    }

    public function build()
    {
        $start = new FrozenDate($this->month . '-01');
        $end = $start->endOfMonth();
        $today = FrozenDate::today();

        $conditions = [
            'Loans.loaned_on >=' => $start,
            'Loans.loaned_on <=' => $end,
        ];

        $this->loanCount = $this->Loans->find()->where($conditions)->count();

        $this->overdueCount = $this->Loans->find()
            ->where($conditions)
            ->where(['OR' => [
                'Loans.returned_on > Loans.due_on',
                ['Loans.returned_on IS' => null, 'Loans.due_on <' => $today],
            ]])
            ->count();

        $query = $this->Loans->find();
        $this->ranking = $query
            ->select(['Loans.item_id', 'loan_count' => $query->func()->count('*')])
            ->contain(['Items'])
            ->enableAutoFields(false)
            ->select($this->Loans->Items)
            ->where($conditions)
            ->group(['Loans.item_id'])
            ->order(['loan_count' => 'DESC', 'Items.code' => 'ASC'])
            ->limit(5)
            ->all();

        $this->title = $start->format('Y年n月') . 'の貸出レポート';
    }
}
