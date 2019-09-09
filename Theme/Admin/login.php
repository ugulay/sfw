<?php $this->extend('Admin/layout/noNavbar'); ?>

<?php $this->block('title', 'Yönetici Girişi'); ?>

<?php $this->block('main'); ?>

    <div class="container h-100">

    <div class="row  justify-content-center align-items-center  h-100">

        <div class="col-4">

            <?php

            echo tokenize();

            if (Kernel\Session::getFlash('error')) { ?>

                <div class="alert alert-danger">
                    Blog yazısı bulunamadı
                </div>

            <?php } ?>

            <form action="/admin/loginCheck">
                <div class="card">
                    <div class="card-header p-1 text-center">
                        Yönetim
                    </div>
                    <div class="card-body p-1">
                        <input type="text" name="username" class="form-control form-control-sm mb-1" placeholder="Kullanıcı adı"/>
                        <input type="text" name="password" class="form-control form-control-sm mb-1" placeholder="Parola"/>
                        <button type="submit" class="btn btn-block btn-sm btn-primary">Giriş</button>
                    </div>
                </div>
            </form>

        </div>

    </div>

<?php $this->endBlock('main'); ?>