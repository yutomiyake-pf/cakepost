<?php echo $this->Html->docType(); ?>
<html lang="ja">
<head>
    <?php echo $this->Html->charset(); ?>
    <?php echo $this->Html->css('style.css'); ?>
    <title>ひとこと掲示板</title>
</head>
<body>
    <?php echo $this->fetch('content'); ?>
</body>
</html>