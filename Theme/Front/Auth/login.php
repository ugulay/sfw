<?php $this->extend('Front/layout/main');?>

<?php $this->block('title', __('auth.loginFormTitle'));?>

<?php $this->block('main');?>

<div class="row justify-content-center">

        <div class="col-4">

        <?php if (\Kernel\Session::flash('info')) {?>
                <div class="alert alert-danger"><?=\Kernel\Session::flash('info')?></div>
        <?php }?>

                <div class="card card-default">

                        <form class="form" action="/auth" method="post">

                        <div class="card-header">
                                <span class="card-title p-0 m-0">Oturum Doğrulama</span>
                        </div>

                        <div class="collapse show" id="demo">

                                <div class="card-body">
                                        <div class="form-group">
                                                <label>E-Posta Adresi</label>
                                                <input type="email" name="email" class="form-control" required="">
                                        </div>
                                        <div class="form-group">
                                                <label>Parola</label>
                                                <input type="password" name="password" class="form-control" required="" minlength="6">
                                        </div>
                                </div>

                                <div class="card-footer">
                                        <a href="/auth/registration" class="btn btn-link float-left">Yeni Kullanıcı</a>
                                        <button type="submit" class="btn btn-primary float-right">Oturum Aç</button>
                                        <div class="clearfix"></div>
                                </div>

                        </div>

                        </form>

                </div>

        </div>

</div>


<?php $this->endBlock('main');?>