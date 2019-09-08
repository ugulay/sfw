<?php $this->extend('Admin/layout/noNavbar'); ?>

<?php $this->block('title', 'BAŞLIK 2'); ?><?php $this->endBlock('title'); ?>

<?php $this->block('main'); ?>

    <div class="container py-3">

        <?php if (Kernel\Session::getFlash('error')) { ?>

            <div class="alert alert-info">
                Blog yazısı bulunamadı
            </div>

        <?php } ?>

        <form>
            <div class="form-group">
                <input type="text" class="form-control"/>
            </div>
        </form>

    </div>

<?php $this->endBlock('main'); ?>