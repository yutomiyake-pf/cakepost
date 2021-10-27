<div id='wrap'>
    <div id='head'>
        <h1>ひとこと掲示板</h1>
    </div>
    <h1><?php echo $this->Session->flash(); ?></h1>
<div id="content">
    <div style="text-align: right">
        <?php echo $this->Html->link('ログアウト',['controller' => 'Members','action' => 'logout']); ?>
        <?php echo $this->Html->link('退会',['controller' => 'Members', 'action' => 'deleteMember'],['confirm' => '退会しますか？']); ?>
    </div>
    <?php echo $this->Form->create(['Post']);
    ?>
    <dl>
        <dt>
            <?php echo h($userName) ?>さんメッセージをどうぞ
            <p style='color: red'>メッセージは100文字以内で入力してください</p>
        </dt>
        <dd>
            <?php echo $this->Form->textarea('message',[
                'cols' => 50, 'rows' => 5,
            ]);
            ?>
            
        </dd>
    </dl>
    <div>
        <?php echo $this->Form->end(__('投稿')); ?>
    </div>

<?php foreach($posts as $post): ?>
<div class="msg">
    <p>
        <!-- 画像をとってくる -->
        <?php echo $this->Html->image("/img/Member_image/" . h($post['Member']['picture']),[
            'width' => 48,
            'height' => 48,
            'alt' => h($post['Member']['username'])
        ]); ?>

        <!-- 投稿メッセージ -->
        <?php echo h($post['Post']['message']); ?>

        <!-- 投稿者 -->
        <span class="name">(<?php echo h($post['Member']['username']); ?>)</span>
        [<?php echo $this->Html->link('Re',['action' => 'res' , $post['Post']['id']]); ?>]
    </p>

    <p class="day">
        <!-- 投稿日時 -->
        <?php echo h($post['Post']['created']); ?>

        <?php if($post['Post']['reply_post_id'] > 0): ?>
            <?php echo $this->Html->link('返信元のメッセージ',['action' => 'view' , $post['Post']['reply_post_id']]); ?>
        <?php endif; ?>

        <?php if($userId == $post['Post']['member_id']): ?>
            <?php echo $this->Form->postLink('削除',['action' => 'delete' , h($post['Post']['id'])],['confirm' => '削除しますか？']); ?>
            <?php echo $this->Html->link('編集',['action' => 'edit' , h($post['Post']['id'])]); ?>
        <?php endif; ?>
    </p>

</div>
<?php endforeach; ?>

<ul class="paging">
    <?php echo $this->Paginator->prev('前へ'); ?>
    <?php echo $this->Paginator->next('次へ'); ?>

</ul>
</div>
</div>

