<?php $this->extend('Front/layout/main');?>

<?php $this->block('title', __('auth.registrationFormTitle'))?>

<?php $this->block('main');?>

<div class="row justify-content-center">

        <div class="col-4">

        <?php if (\Kernel\Session::flash('info')) {?>
                <div class="alert alert-danger"><?=\Kernel\Session::flash('info')?></div>
        <?php }?>

                <div class="card card-default">

                    <form class="form" href="/auth/registration" type="post">

                        <div class="card-header">
                                <span class="card-title p-0 m-0"><?=__('auth.registrationFormTitle')?></span>
                        </div>

                        <div class="card-body">

                            <div class="form-group">
                                <label>Ad *</label>
                                <input type="text" name="name" class="form-control" required="">
                            </div>

                            <div class="form-group">
                                <label>Soyad *</label>
                                <input type="text" name="surname" class="form-control" required="">
                            </div>

                            <div class="form-group">
                                <label>E-Posta Adresi *</label>
                                <input type="email" name="email" class="form-control" required="">
                            </div>

                            <div class="form-group">
                                <label>Parola *</label>
                                <input type="password" name="password" class="form-control" required="">
                            </div>
                            
                            <div class="form-group">
                                <label>Parola (Tekrarı) *</label>
                                <input type="password" name="password2" class="form-control" required="">
                            </div>

                            <div class="form-group">
                                <label>Doğum Tarihi</label>
                                <input type="date" name="birthday" class="form-control">
                            </div>

                            <div class="form-group">

                            </div>

                        </div>

                        <div class="card-footer">
                            <a href="/auth" class="btn btn-link float-left">Kayıtlı Bir Üyeyim</a>
                            <button type="submit" class="btn btn-primary float-right">Kayıt Ol</button>
                            <div class="clearfix"></div>
                        </div>

                    </form>

                </div>

        </div>

</div>

<?php $this->endBlock('main');?>