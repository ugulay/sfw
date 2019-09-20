
<?php $this->extend('Front/layout/main');?>

<?php $this->block('title', 'Aradığınız sayfa bulunamadı.');?>

<?php $this->block('main');?>

        <h1 class="alert alert-danger p-5 text-center"><?=__('error.pageNotFound')?></h1>

<?php $this->endBlock('main');?>