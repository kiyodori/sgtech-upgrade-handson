<?php $this->assign('title', 'ログイン'); ?>
<div class="card narrow">
    <h1>ログイン</h1>
    <?= $this->Form->create() ?>
    <?= $this->Form->control('email', ['label' => 'メールアドレス', 'type' => 'email']) ?>
    <?= $this->Form->control('password', ['label' => 'パスワード']) ?>
    <?= $this->Form->button('ログイン', ['class' => 'button']) ?>
    <?= $this->Form->end() ?>
    <p class="hint">例: taro@example.com / password</p>
</div>
