<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class LoansTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('loans');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Items');
        $this->belongsTo('Users');
    }

    public function validationDefault(Validator $validator)
    {
        $validator
            ->requirePresence('due_on', 'create')
            ->date('due_on', ['ymd'], '返却予定日を入力してください。');

        return $validator;
    }
}
