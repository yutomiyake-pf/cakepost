<h1>ログイン画面</h1>
<p>登録しているメールアドレスとパスワードを入力してください</p>

<?php
    echo $this->Session->flash();
    echo $this->Form->create('Member');
    echo $this->Form->input('email',[
        'label' => 'メールアドレス'
    ]);
    echo $this->Form->input('password',[
        'label' => 'パスワード'
    ]);
    echo $this->Form->end(__('Login'));
    echo $this->Html->link('会員登録をしていない方はこちら', ['action' => 'register']);
?>
