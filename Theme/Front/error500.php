
<?php $this->extend('Front/layout/main');?>

<?php $this->block('title','Aradığınız sayfa bulunamadı.');?>

<?php $this->block('main');?>

    <div class="container py-3">

        <h1 class="alert alert-danger p-5 text-center">Error 500.</h1>

    </div>

<?php $this->endBlock('main');?>