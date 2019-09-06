
<?php $this->extend('Front/layout/main');?>

<?php $this->block('title', $data['title']);?>

<?php $this->block('main');?>

    <div class="container py-3">

            <h1 class="h3"><?=$data['title']?></h1>

            <hr>

            <?=$data["text"]?>

    </div>

<?php $this->endBlock('main');?>