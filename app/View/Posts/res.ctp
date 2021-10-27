<div id='wrap'>
    <div id='head'>
        <h1>ひとこと掲示板</h1>
    </div>

<div id="content">
    <p style='color: red'>メッセージは1～100文字以内にしてください</p>
    <?php echo $this->Form->create(['Post']);
    ?>
    <dl>
        <dt>
            <?php echo h($userName); ?>さんメッセージをどうぞ
        </dt>
        <dd>
            <?php echo $this->Form->textarea('message',[
                'cols' => 50, 'rows' => 5, 'value' => h($message)
            ]);
            ?>
            <p><?php echo $this->Session->flash(); ?></p>
        </dd>
    </dl>
    <div>
        <?php echo $this->Form->end(__('返信')); ?>
    </div>
</div>
</div>
