<?php
use App\Model\Entity\Item;

$this->assign('title', $item->name);
?>
<div class="title-row">
    <h1><?= h($item->name) ?></h1>
    <div class="actions">
        <?php if ($item->status === Item::STATUS_AVAILABLE) : ?>
            <?= $this->Html->link('貸し出す', ['controller' => 'Loans', 'action' => 'add', $item->id], ['class' => 'button']) ?>
        <?php endif; ?>
        <?= $this->Html->link('編集', ['action' => 'edit', $item->id], ['class' => 'button secondary']) ?>
    </div>
</div>

<table class="vertical">
    <tr><th>管理番号</th><td><?= h($item->code) ?></td></tr>
    <tr><th>分類</th><td><?= h($item->category) ?></td></tr>
    <tr><th>状態</th><td><span class="badge status-<?= $item->status ?>"><?= h($item->status_label) ?></span></td></tr>
    <tr><th>備考</th><td><?= nl2br(h($item->note)) ?></td></tr>
    <tr><th>これまでの貸出者</th><td><?= h($borrowers) ?></td></tr>
</table>

<h2>貸出履歴</h2>
<?php if (empty($item->loans)) : ?>
    <p>まだ貸し出されていません。</p>
<?php else : ?>
    <table>
        <thead>
            <tr><th>借りた人</th><th>貸出日</th><th>返却予定日</th><th>返却日</th><th>付属品</th><th></th></tr>
        </thead>
        <tbody>
            <?php foreach ($item->loans as $loan) : ?>
                <tr>
                    <td><?= h($loan->user->name) ?></td>
                    <td><?= h($loan->loaned_on->format('Y/m/d')) ?></td>
                    <td class="<?= $loan->is_overdue ? 'danger' : '' ?>"><?= h($loan->due_on->format('Y/m/d')) ?></td>
                    <td><?= $loan->returned_on ? h($loan->returned_on->format('Y/m/d')) : '' ?></td>
                    <td><?= h($loan->accessories) ?>（<?= (int)$loan->accessory_count ?>点）</td>
                    <td>
                        <?php if ($loan->returned_on === null) : ?>
                            <?= $this->Form->postLink('返却', ['controller' => 'Loans', 'action' => 'returnItem', $loan->id], ['class' => 'button small', 'confirm' => '返却を記録しますか？']) ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
