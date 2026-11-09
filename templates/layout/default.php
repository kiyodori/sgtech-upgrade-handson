<!DOCTYPE html>
<html lang="ja">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($this->fetch('title')) ?> | 備品貸出管理</title>
    <?= $this->Html->meta('icon') ?>
    <?= $this->Html->css('app') ?>
</head>
<body>
    <header class="header">
        <div class="header-inner">
            <?= $this->Html->link('備品貸出管理', '/', ['class' => 'brand']) ?>
            <?php if (!empty($currentUser)) : ?>
                <nav class="nav">
                    <?= $this->Html->link('ダッシュボード', ['controller' => 'Dashboard', 'action' => 'index']) ?>
                    <?= $this->Html->link('備品一覧', ['controller' => 'Items', 'action' => 'index']) ?>
                    <?= $this->Html->link('月次レポート', ['controller' => 'Reports', 'action' => 'monthly']) ?>
                </nav>
                <div class="user">
                    <?= h($currentUser['name']) ?> さん
                    <?= $this->Html->link('ログアウト', ['controller' => 'Users', 'action' => 'logout']) ?>
                </div>
            <?php endif; ?>
        </div>
    </header>
    <main class="main">
        <?= $this->Flash->render() ?>
        <?= $this->fetch('content') ?>
    </main>
</body>
</html>
