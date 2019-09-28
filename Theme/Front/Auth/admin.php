<?php $this->extend('Front/layout/main');?>

<?php $this->block('title', __('auth.loginFormTitle'));?>

<?php $this->block('main');?>

<div class="row justify-content-center">

        <div class="col-4">

        <?php $errors = \Kernel\Session::flash('error'); if ($errors) {?>
                <div class="alert alert-danger"><?=$errors?></div>
        <?php }?>

                <div class="card card-default">

                        <form class="form" action="/auth/admin" method="post">

                        <div class="card-header">
                                <span class="card-title p-0 m-0"><?=__('auth.loginFormTitle')?></span>
                        </div>

                        <div class="collapse show" id="demo">

                                <div class="card-body">
                                        <div class="form-group">
                                                <label><?=__('auth.emailAddress')?></label>
                                                <input type="email" name="email" class="form-control" required="" value="<?=old('email')?>">
                                        </div>
                                        <div class="form-group">
                                                <label><?=__('auth.password')?></label>
                                                <input type="password" name="password" class="form-control" required="" value="<?=old('password')?>">
                                        </div>
                                </div>

                                <div class="card-footer">
                                        <button type="submit" class="btn btn-primary float-right"><?=__('auth.login')?></button>
                                        <div class="clearfix"></div>
                                </div>

                        </div>

                        </form>

                </div>

        </div>

</div>


<?php $this->endBlock('main');?>