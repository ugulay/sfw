<!doctype html>
<html lang="tr" class="h-100">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="/Public/css/main.css">

    <title><?=$this->placeholder('title', 'BAŞLIK');?></title>
  </head>
  <body class="h-100">

  <nav class="navbar navbar-expand-lg navbar-light bg-light">

  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

    <div class="collapse navbar-collapse" id="navbarTogglerDemo01">

      <a class="navbar-brand" href="/">Görev Tanımı</a>

      <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
      <?php if (!\Kernel\Session::get('auth')) {?>
        <li class="nav-item">
          <a class="nav-link" href="/auth"><?=__('auth.login')?></a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="/auth/registration"><?=__('auth.register')?></a>
        </li>
      <?php }else{ ?>
        <li class="nav-item">
          <a class="nav-link" href="/auth/logout"><?=__('auth.logout')?></a>
        </li>
      <?php } ?>

      </ul>

  </div>

</nav>

<div class="container-fluid mainContent py-3">