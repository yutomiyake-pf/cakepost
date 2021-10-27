<h1>登録が完了しました!</h1>
<p>ログイン画面にお進みください</p>

<?php 
    echo $this->Html->link(
                    'ログイン画面へ進む',
                    [
                        'action' => 'login'
                    ]
    );
?>