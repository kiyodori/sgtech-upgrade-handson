<?php
namespace App\Controller;

use App\Model\Entity\Item;
use Cake\ORM\Query;

class ItemsController extends AppController
{
    public function index()
    {
        $status = $this->request->getQuery('status', '');

        $items = $this->filterByStatus($this->Items->find(), $status)
            ->order(['Items.code' => 'ASC'])
            ->all();

        $this->set(compact('items', 'status'));
    }

    public function view($id = null)
    {
        $item = $this->Items->get($id, [
            'contain' => [
                'Loans' => [
                    'Users',
                    'sort' => ['Loans.loaned_on' => 'DESC'],
                ],
            ],
        ]);

        $names = [];
        foreach ($item->loans as $loan) {
            $names[] = $loan->user->name;
        }
        $borrowers = implode(array_unique($names), '、');

        $this->set(compact('item', 'borrowers'));
    }

    public function add()
    {
        $item = $this->Items->newEmptyEntity();
        if ($this->request->is('post')) {
            $item = $this->Items->patchEntity($item, $this->request->getData());
            if ($this->Items->save($item)) {
                $this->Flash->success('備品を登録しました。');

                return $this->redirect(['action' => 'view', $item->id]);
            }
            $this->Flash->error('登録できませんでした。入力内容を確認してください。');
        }
        $this->set(compact('item'));
    }

    public function edit($id = null)
    {
        $item = $this->Items->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $item = $this->Items->patchEntity($item, $this->request->getData());
            if ($this->Items->save($item)) {
                $this->Flash->success('備品を更新しました。');

                return $this->redirect(['action' => 'view', $item->id]);
            }
            $this->Flash->error('更新できませんでした。入力内容を確認してください。');
        }
        $this->set(compact('item'));
    }

    public function export()
    {
        $status = $this->request->getQuery('status', '');
        $items = $this->filterByStatus($this->Items->find(), $status)
            ->order(['Items.code' => 'ASC'])
            ->all();

        $excel = new \PHPExcel();
        $sheet = $excel->getActiveSheet();
        $sheet->setTitle('備品一覧');
        $sheet->fromArray(['管理番号', '名前', '分類', '状態', '備考'], null, 'A1');
        $row = 2;
        foreach ($items as $item) {
            $sheet->fromArray([
                $item->code,
                $item->name,
                $item->category,
                $item->status_label,
                $item->note,
            ], null, 'A' . $row);
            $row++;
        }
        foreach (['A' => 12, 'B' => 30, 'C' => 16, 'D' => 10, 'E' => 60] as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }

        $writer = \PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        ob_start();
        $writer->save('php://output');
        $body = ob_get_clean();

        return $this->response
            ->withType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->withStringBody($body)
            ->withDownload('items.xlsx');
    }

    private function filterByStatus(Query $query, $status)
    {
        if ($status == Item::STATUS_AVAILABLE) {
            $query->where(['Items.status' => Item::STATUS_AVAILABLE]);
        } elseif ($status == Item::STATUS_LOANED) {
            $query->where(['Items.status' => Item::STATUS_LOANED]);
        } elseif ($status == Item::STATUS_REPAIR) {
            $query->where(['Items.status' => Item::STATUS_REPAIR]);
        }

        return $query;
    }
}
