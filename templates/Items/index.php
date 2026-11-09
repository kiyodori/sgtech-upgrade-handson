<?php
use App\Model\Entity\Item;

$this->assign('title', '備品一覧');
?>
<div class="title-row">
    <h1>備品一覧</h1>
    <?= $this->Html->link('備品を登録', ['action' => 'add'], ['class' => 'button']) ?>
</div>

<?= $this->Form->create(null, ['type' => 'get', 'class' => 'filter']) ?>
<?= $this->Form->control('status', [
    'label' => '状態',
    'type' => 'select',
    'options' => Item::STATUS_LABELS,
    'empty' => 'すべて',
    'value' => $status,
]) ?>
<?= $this->Form->button('絞り込む', ['class' => 'button secondary']) ?>
<?= $this->Html->link('Excel 出力', ['action' => 'export', '?' => ['status' => $status]], ['class' => 'button secondary']) ?>
<?= $this->Form->end() ?>

<table>
    <thead>
        <tr><th>管理番号</th><th>名前</th><th>分類</th><th>状態</th><th>備考</th></tr>
    </thead>
    <tbody>
        <?php foreach ($items as $item) : ?>
            <tr>
                <td><?= h($item->code) ?></td>
                <td><?= $this->Html->link($item->name, ['action' => 'view', $item->id]) ?></td>
                <td><?= h($item->category) ?></td>
                <td><span class="badge status-<?= $item->status ?>"><?= h($item->status_label) ?></span></td>
                <td class="note">
                    <?php if (mb_strlen((string)$item->note) > 30) : ?>
                        <?= h(mb_substr($item->note, 0, 30)) ?>…
                    <?php else : ?>
                        <?= h($item->note) ?>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<p class="hint"><?= count($items) ?> 件</p>
