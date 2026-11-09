<?php $this->assign('title', '備品の登録'); ?>
<div class="card">
    <h1>備品の登録</h1>
    <?= $this->element('item_form', ['item' => $item]) ?>
</div>
