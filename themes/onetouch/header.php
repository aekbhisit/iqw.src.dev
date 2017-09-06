<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Onetouch</title>
    <link rel="stylesheet" type="text/css" media="all" href="<?=THEME_ROOT_URL?>css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" media="all" href="<?=THEME_ROOT_URL?>css/bootstrap-reboot.min.css">
    <link rel="stylesheet" type="text/css" media="all" href="<?=THEME_ROOT_URL?>css/bootstrap-formhelpers.min.css">
    <link rel="stylesheet" type="text/css" media="all" href="<?=THEME_ROOT_URL?>css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" media="all" href="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.2.0/ekko-lightbox.min.css">
    <link rel="stylesheet" type="text/css" media="all" href="<?=THEME_ROOT_URL?>owlcarousel/assets/owl.carousel.min.css">
    <link rel="stylesheet" type="text/css" media="all" href="<?=THEME_ROOT_URL?>owlcarousel/assets/owl.theme.default.min.css">
    <link rel="stylesheet" type="text/css" media="all" href="<?=THEME_ROOT_URL?>css/theme.min.css">
</head>

<body class="home">
    <header id="masthead" class="fixed-top">
        <nav class="navbar navbar-expand-lg container">
            <a class="navbar-brand" href="index.php"><img src="<?=THEME_ROOT_URL?>images/theme/logo.png" /></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarsExampleDefault">
                <ul class="navbar-nav ml-auto">
                <?php
                    $pages = array( '/' => 'Home', '/about' => 'About', '/products' => 'Products', '/news' => 'News & Events', '/gallery' => 'Gallery', '#footer-contact' => 'Contact' );
                    foreach ( $pages as $link => $page ) {
                        $class_active = ( $link == basename( $_SERVER['SCRIPT_FILENAME'] ) ) ? 'active' : '';
                ?>
                    <li class="nav-item active">
                        <a class="nav-link <?php echo $class_active; ?>" href="/<?=LANG?><?php echo $link; ?>"><?php echo $page; ?></a>
                    </li>
                <?php
                    }
                ?>
                    <li class="nav-item dropdown languages">
                        <a class="nav-link dropdown-toggle" href="/" id="dropdown-lang" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">EN</a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-lang">
                            <a class="dropdown-item" href="#">EN</a>
                            <a class="dropdown-item" href="#">TH</a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <main id="main" class="site-main" role="main">