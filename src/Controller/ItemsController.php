<?php
namespace App\Controller;

use App\Model\Entity\Item;
use Cake\ORM\Query;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ItemsController extends AppController
{
    public function index()
    {
        // 開いたときは貸出可だけを出す。「すべて」を選ぶと全件を出す
        $status = $this->request->getQuery('status', (string)Item::STATUS_AVAILABLE);

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
        $borrowers = implode('、', array_unique($names));

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
        $status = $this->request->getQuery('status', (string)Item::STATUS_AVAILABLE);
        $items = $this->filterByStatus($this->Items->find(), $status)
            ->order(['Items.code' => 'ASC'])
            ->all();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
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

        $writer = new Xlsx($spreadsheet);
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
        if ($status === '') {
            return $query;
        }
        if (array_key_exists((int)$status, Item::STATUS_LABELS)) {
            $query->where(['Items.status' => (int)$status]);
        }

        return $query;
    }
}
