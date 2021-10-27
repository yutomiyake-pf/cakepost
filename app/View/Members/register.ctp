<h1>会員登録</h1>
<p>次のフォームに必要事項を入力してください</p>

<div>

    <?php 
        echo $this->Session->flash();
        echo $this->Form->create('Member',[
            'type' => 'file',
            'enctype' => 'multipart/form-data'
        ]);
        echo $this->Form->input('username',[
            'label' => 'ニックネーム　必須',
            'type' => 'text'
        ]);
        echo $this->Form->input('email',[
            'label' => 'メールアドレス　必須'
        ]);
        echo $this->Form->input('password',[
            'label' => 'パスワード　必須'
        ]);

        echo $this->Form->input('picture',[
            'type' => 'file',
            'label' => '写真など 必須',
        ]);

        echo $this->Form->end(__('入力内容を登録する'));
    ?>

</div>