<?php $this->extend('Front/layout/main');?>

<?php $this->block('title', __('auth.registrationFormTitle'))?>

<?php $this->block('main');?>

<div class="row justify-content-center">

        <div class="col-4">

        <?php $success = \Kernel\Session::flash('success'); if ($success) {?>
                <div class="alert alert-success"><?=$success?></div>
        <?php }?>

        <?php $errors = \Kernel\Session::flash('error'); if ($errors) {?>
                <div class="alert alert-danger"><?=$errors?></div>
        <?php }?>

        </div>

</div>

<?php $this->endBlock('main');?>