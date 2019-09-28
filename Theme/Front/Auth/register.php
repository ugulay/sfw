<?php $this->extend('Front/layout/main');?>

<?php $this->block('title', __('auth.registrationFormTitle'))?>

<?php $this->block('main');?>

<div class="row justify-content-center">

        <div class="col-4">

        <?php
        $success = \Kernel\Session::flash('success');
        if ($success) {?>
            <div class="alert alert-success">
                <ul class="list m-0 p-0 px-3">
                    <?php foreach ($success as $success) {?>
                        <li><?=$success?></li>
                    <?php }?>
                </ul>
            </div>
        <?php }?>

        <?php
        $errors = \Kernel\Session::flash('error');
        if ($errors) {?>
                <div class="alert alert-danger">
                    <ul class="list m-0 p-0 px-3">
                        <?php foreach ($errors as $error) {?>
                            <li><?=$error?></li>
                        <?php }?>
                    </ul>
                </div>
        <?php }?>

                <div class="card card-default">

                    <form class="form" action="/auth/registration" method="post">

                        <div class="card-header">
                                <span class="card-title p-0 m-0"><?=__('auth.registrationFormTitle')?></span>
                        </div>

                        <div class="card-body">

                            <div class="form-group">
                                <label><?=__('auth.name')?> *</label>
                                <input type="text" name="name" class="form-control" value="<?=old("name")?>">
                            </div>

                            <div class="form-group">
                                <label><?=__('auth.surname')?> *</label>
                                <input type="text" name="surname" class="form-control" value="<?=old("surname")?>">
                            </div>

                            <div class="form-group">
                                <label><?=__('auth.emailAddress')?> *</label>
                                <input type="text" name="email" class="form-control" value="<?=old("email")?>">
                            </div>

                            <div class="form-group">
                                <label><?=__('auth.password')?> *</label>
                                <input type="password" name="password" class="form-control" >
                            </div>

                            <div class="form-group">
                                <label><?=__('auth.passwordRepeat')?> *</label>
                                <input type="password" name="password2" class="form-control" >
                            </div>

                        </div>

                        <div class="card-footer">
                            <a href="/auth" class="btn btn-link float-left"><?=__('auth.alreadyRegisteredLogin')?></a>
                            <button type="submit" class="btn btn-primary float-right"><?=__('auth.register')?></button>
                            <div class="clearfix"></div>
                        </div>

                    </form>

                </div>

        </div>

</div>

<?php $this->endBlock('main');?>