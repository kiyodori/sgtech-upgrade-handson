<?php $this->assign('title', '備品の編集'); ?>
<div class="card">
    <h1>備品の編集</h1>
    <?= $this->element('item_form', ['item' => $item]) ?>
</div>
