<?php $this->assign('title', 'ダッシュボード'); ?>
<h1>ダッシュボード</h1>

<div class="stats">
    <div class="stat">
        <div class="stat-label">貸出中</div>
        <div class="stat-value"><?= (int)$counts['loaned'] ?><span>件</span></div>
    </div>
    <div class="stat">
        <div class="stat-label">延滞中</div>
        <div class="stat-value danger"><?= (int)$counts['overdue'] ?><span>件</span></div>
    </div>
</div>

<h2>延滞中の貸出</h2>
<?php if ($overdueLoans->isEmpty()) : ?>
    <p>延滞中の貸出はありません。</p>
<?php else : ?>
    <table>
        <thead>
            <tr><th>管理番号</th><th>備品</th><th>借りている人</th><th>返却予定日</th></tr>
        </thead>
        <tbody>
            <?php foreach ($overdueLoans as $loan) : ?>
                <tr>
                    <td><?= h($loan->item->code) ?></td>
                    <td><?= $this->Html->link($loan->item->name, ['controller' => 'Items', 'action' => 'view', $loan->item->id]) ?></td>
                    <td><?= h($loan->user->name) ?></td>
                    <td class="danger"><?= h($loan->due_on->format('Y/m/d')) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
