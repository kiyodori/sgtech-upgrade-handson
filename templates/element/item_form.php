<?php
use App\Model\Entity\Item;
?>
<?= $this->Form->create($item) ?>
<?= $this->Form->control('code', ['label' => '管理番号']) ?>
<?= $this->Form->control('name', ['label' => '名前']) ?>
<?= $this->Form->control('category', [
    'label' => '分類',
    'type' => 'select',
    'options' => array_combine(Item::CATEGORIES, Item::CATEGORIES),
    'empty' => '選んでください',
]) ?>
<?= $this->Form->control('note', ['label' => '備考', 'type' => 'textarea', 'rows' => 3]) ?>
<?= $this->Form->button('保存', ['class' => 'button']) ?>
<?= $this->Form->end() ?>
