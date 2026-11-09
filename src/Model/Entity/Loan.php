<?php
namespace App\Model\Entity;

use Cake\I18n\FrozenDate;
use Cake\ORM\Entity;

class Loan extends Entity
{
    protected $_accessible = [
        'due_on' => true,
        'accessories' => true,
        'accessory_count' => true,
    ];

    protected function _getIsOverdue()
    {
        return $this->returned_on === null && $this->due_on < FrozenDate::today();
    }
}
