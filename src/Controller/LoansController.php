<?php
namespace App\Controller;

use App\Model\Entity\Item;
use Cake\Cache\Cache;
use Cake\I18n\FrozenDate;

class LoansController extends AppController
{
    public function add($itemId = null)
    {
        $item = $this->Loans->Items->get($itemId);
        if ($item->status !== Item::STATUS_AVAILABLE) {
            $this->Flash->error('この備品はいま貸し出せません。');

            return $this->redirect(['controller' => 'Items', 'action' => 'view', $item->id]);
        }

        $loan = $this->Loans->newEmptyEntity();
        if ($this->request->is('post')) {
            $accessories = $this->request->getData('accessories') ?? [];

            $loan = $this->Loans->patchEntity($loan, [
                'due_on' => $this->request->getData('due_on'),
                'accessories' => $accessories ? implode('、', $accessories) : '',
                'accessory_count' => count($accessories),
            ]);
            $loan->item_id = $item->id;
            $loan->user_id = $this->Auth->user('id');
            $loan->loaned_on = FrozenDate::today();

            $item->status = Item::STATUS_LOANED;

            $saved = $this->Loans->getConnection()->transactional(function () use ($loan, $item) {
                return $this->Loans->save($loan) && $this->Loans->Items->save($item);
            });
            if ($saved) {
                Cache::delete('dashboard_counts');
                $this->Flash->success('貸出を登録しました。');

                return $this->redirect(['controller' => 'Items', 'action' => 'view', $item->id]);
            }
            $this->Flash->error('登録できませんでした。入力内容を確認してください。');
        }

        $accessoryOptions = [
            '充電器' => '充電器',
            'ケース' => 'ケース',
            'マウス' => 'マウス',
        ];
        $defaultDueOn = FrozenDate::today()->addDays(7)->format('Y-m-d');
        $this->set(compact('item', 'loan', 'accessoryOptions', 'defaultDueOn'));
    }

    public function returnItem($id = null)
    {
        $this->request->allowMethod(['post']);

        $loan = $this->Loans->get($id, ['contain' => ['Items']]);
        $loan->returned_on = FrozenDate::today();
        $item = $loan->item;
        $item->status = Item::STATUS_AVAILABLE;

        $saved = $this->Loans->getConnection()->transactional(function () use ($loan, $item) {
            return $this->Loans->save($loan) && $this->Loans->Items->save($item);
        });
        if ($saved) {
            Cache::delete('dashboard_counts');
            $this->Flash->success('返却を記録しました。');
        } else {
            $this->Flash->error('返却を記録できませんでした。');
        }

        return $this->redirect(['controller' => 'Items', 'action' => 'view', $item->id]);
    }
}
