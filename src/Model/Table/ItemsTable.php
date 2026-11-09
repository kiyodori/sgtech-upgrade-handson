<?php
namespace App\Model\Table;

use App\Model\Entity\Item;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class ItemsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('items');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Loans');
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->requirePresence('code', 'create')
            ->notEmptyString('code', '管理番号を入力してください。')
            ->maxLength('code', 20, '管理番号は20文字以内で入力してください。')
            ->add('code', 'unique', [
                'rule' => 'validateUnique',
                'provider' => 'table',
                'message' => 'この管理番号はすでに使われています。',
            ]);

        $validator
            ->requirePresence('name', 'create')
            ->notEmptyString('name', '名前を入力してください。')
            ->maxLength('name', 100, '名前は100文字以内で入力してください。');

        $validator
            ->requirePresence('category', 'create')
            ->inList('category', Item::CATEGORIES, '分類を選んでください。');

        $validator
            ->allowEmptyString('note');

        return $validator;
    }
}
