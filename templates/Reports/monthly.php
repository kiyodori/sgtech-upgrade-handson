<?php $this->assign('title', '月次レポート'); ?>
<h1><?= h($report->title) ?></h1>

<?= $this->Form->create(null, ['type' => 'get', 'class' => 'filter']) ?>
<div class="input">
    <label for="month">対象月</label>
    <input type="month" name="month" id="month" value="<?= h($month) ?>">
</div>
<?= $this->Form->button('表示', ['class' => 'button secondary']) ?>
<?= $this->Form->end() ?>

<div class="stats">
    <div class="stat">
        <div class="stat-label">貸出件数</div>
        <div class="stat-value"><?= (int)$report->loanCount ?><span>件</span></div>
    </div>
    <div class="stat">
        <div class="stat-label">延滞件数</div>
        <div class="stat-value danger"><?= (int)$report->overdueCount ?><span>件</span></div>
    </div>
</div>

<h2>よく借りられた備品</h2>
<?php if ($report->ranking->isEmpty()) : ?>
    <p>この月の貸出はありません。</p>
<?php else : ?>
    <table>
        <thead>
            <tr><th>順位</th><th>管理番号</th><th>備品</th><th>回数</th></tr>
        </thead>
        <tbody>
            <?php foreach ($report->ranking as $i => $row) : ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= h($row->item->code) ?></td>
                    <td><?= $this->Html->link($row->item->name, ['controller' => 'Items', 'action' => 'view', $row->item->id]) ?></td>
                    <td><?= (int)$row->loan_count ?>回</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
