<?php $this->assign('title', '貸出登録'); ?>
<div class="card">
    <h1>貸出登録</h1>
    <p><?= h($item->code) ?> <?= h($item->name) ?></p>
    <?= $this->Form->create($loan) ?>
    <div class="input date">
        <label for="due-on">返却予定日</label>
        <input type="date" name="due_on" id="due-on" value="<?= h($this->request->getData('due_on', $defaultDueOn)) ?>" required>
        <?php if ($loan->getError('due_on')) : ?>
            <div class="error-message"><?= h(current($loan->getError('due_on'))) ?></div>
        <?php endif; ?>
    </div>
    <?= $this->Form->control('accessories', [
        'label' => '付属品',
        'type' => 'select',
        'multiple' => 'checkbox',
        'options' => $accessoryOptions,
        'hiddenField' => false,
    ]) ?>
    <?= $this->Form->button('貸し出す', ['class' => 'button']) ?>
    <?= $this->Form->end() ?>
</div>
