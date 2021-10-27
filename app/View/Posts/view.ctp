<div id='wrap'>
    <div id='head'>
        <h1>ひとこと掲示板</h1>
    </div>

<div id="content">
    <dl>
        <dt>
            <?php echo h($memberName); ?>さんのメッセージ
        </dt>
        <dd>
            「<?php echo h($memberMessage); ?>」
            <div>
                <?php echo h($messageDate); ?>
            </div>
        </dd>
    </dl>
    <?php echo $this->Html->link('戻る',['action' => 'index']); ?>
</div>
</div>
