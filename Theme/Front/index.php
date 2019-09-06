
<?php $this->extend('Front/layout/main');?>

<?php $this->block('title','BAŞLIK 2');?><?php $this->endBlock('title');?>

<?php $this->block('main');?>

    <div class="container py-3">

        <?php if (!$data) {?>

            <div class="alert alert-info">
                Blog yazısı bulunamadı
            </div>

        <?php } else {?>

            <h1 class="h3">Son Yazılar</h1>

            <hr>

            <?php foreach ($data as $lastPost) {?>
                <div class="card mb-3">
                    <div class="card-header">
                        <?=$lastPost["title"]?>
                    </div>
                    <div class="card-body">
                        <?=$lastPost["text"]?>
                    </div>
                    <div class="card-footer">
                        <a href="/post/<?=$lastPost["slug"]?>">Okumaya devam et</a>
                    </div>
                </div>
            <?php }?>

        <?php }?>

    </div>

<?php $this->endBlock('main');?>