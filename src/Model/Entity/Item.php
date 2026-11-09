<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class Item extends Entity
{
    const STATUS_AVAILABLE = 0;
    const STATUS_LOANED = 1;
    const STATUS_REPAIR = 2;

    const STATUS_LABELS = [
        self::STATUS_AVAILABLE => '貸出可',
        self::STATUS_LOANED => '貸出中',
        self::STATUS_REPAIR => '修理中',
    ];

    const CATEGORIES = ['ノートPC', 'プロジェクター', 'カメラ', 'モニター', 'その他'];

    protected $_accessible = [
        'code' => true,
        'name' => true,
        'category' => true,
        'note' => true,
    ];

    protected function _getStatusLabel()
    {
        return self::STATUS_LABELS[$this->status];
    }
}
