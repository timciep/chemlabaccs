<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo APP_NAME ?> | <?php echo $title ?></title>
        <link rel="shortcut icon" href="img/favicon.png">
        <?php echo $_styles ?>
        <?php echo $_scripts ?>
        <style type="text/css">
            body {
                padding-top: 80px;
            }
            div.error {
                color: #f00;
            }
        </style>
        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="<?php echo base_url() ?>/js/html5shiv.js"></script>
          <script src="<?php echo base_url() ?>/js/respond.min.js"></script>
        <![endif]-->
        <script>
            $(function() {
            });
        </script>
    </head>
    <body>
        <div class="navbar <?php echo $theme; ?> navbar-default navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <?php echo anchor('', APP_NAME, array('class' => 'navbar-brand')); ?>
                </div>
                <div class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Accidents <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><?php echo anchor("accident/add", '<span class="glyphicon glyphicon-plus"></span> Add'); ?></li>
                                <li><?php echo anchor("accident/search", '<span class="glyphicon glyphicon-search"></span> Search'); ?></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Reporting <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><?php echo anchor("accident/mine", '<span class="glyphicon glyphicon-user"></span> My Reports'); ?></li>
                            </ul>
                        </li>
                    </ul>
                    <?php if (CI()->auth->is_authenticated()): ?>
                        <?php echo $navbar_signed_in ?>
                    <?php else: ?>
                        <?php echo $navbar_sign_in ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="container">
            <?php echo $flash; ?>
            <?php echo @$error; ?>
            <?php if ($heading): ?>
                <div class="page-header">
                    <h2><?php echo $heading; ?></h2>
                </div>
            <?php endif; ?>
            <?php print $content ?>
            <hr />
            <footer>
                <p>&copy; <?php echo APP_NAME ?> <?php echo date("Y"); ?>. All Rights Reserved.</p>
            </footer>
        </div>
    </body>
</html>