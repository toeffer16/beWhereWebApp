<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>BeWhere</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" href="<?php echo base_url("img/favicon.png") ?>">

        <link rel="stylesheet" href="<?php echo base_url("res/css/bootstrap.min.css") ?>">
        <link rel="stylesheet" href="<?php echo base_url("font-awesome-4.3.0/css/font-awesome.min.css") ?>">
        <link rel="stylesheet" href="<?php echo base_url("font-awesome-4.3.0/css/font-awesome.css") ?>">

        <script type="text/javascript" charset="utf8" src="<?php echo base_url("res/js/vendor/jquery-1.11.2.min.js") ?>"></script>
        <script type="text/javascript" charset="utf8" src="<?php echo base_url("res/js/vendor/bootstrap.min.js") ?>"></script>
        <script type="text/javascript" charset="utf8" src="<?php echo base_url("res/js/main.js") ?>"></script>

        <?php echo $custom_heads; ?>

        <style>
            body {
                padding-top: 50px;
                padding-bottom: 20px;        
                //background-image: url('../img/bgfinal.png');
                background-image: url('<?php echo base_url("img/crossword.png"); ?>');
                background-position: center;
                background-repeat: repeat;
                width: 100%;
                height: 100%;
            }

        </style>
        <!-- background-image: url('../img/bgsad.png');
        background-repeat: round; -->
        <!-- <link rel="stylesheet" href="css/bootstrap-theme.min.css"> -->
        <!--<link rel="stylesheet" href="<?php echo base_url("res/css/main.css") ?>"> -->
        <!-- <link href="<?php echo base_url("res/css/styles.css") ?>" rel="stylesheet"> -->

        <script src="<?php echo base_url("res/js/vendor/modernizr-2.8.3-respond-1.4.2.min.js") ?>"></script>
    </head>
    <body>
        <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <a class="navbar-brand" href="<?php echo site_url("administrator") ?>"><img alt="BeWhere" src="<?php echo base_url("img/logo.png") ?>" style="height: 30px; width: 120px;"/></a>

                </div>
                <!--  <div id="navbar" class="navbar-collapse collapse">
                    <form class="navbar-form navbar-right" role="form">
                      <div class="form-group">
                        <input type="text" placeholder="Email" class="form-control">
                      </div>
                      <div class="form-group">
                        <input type="password" placeholder="Password" class="form-control">
                      </div>
                      <button type="submit" class="btn btn-success">Sign in</button>
                    </form>
                  </div> --><!--/.navbar-collapse -->
                <?php if (isset($logged_in) && $logged_in): ?>
                    <div class="collapse navbar-collapse" id="navbar">
                        <ul class="nav navbar-nav navbar- pull-right">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-user">&nbsp;</i><?php echo $username; ?><span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="#"><i class="fa fa-cog fa-sm"></i> Settings</a></li>
                                    <li><a href="<?php echo site_url("administrator/logout") ?>"> <i class="fa fa-sign-out fa-sm"></i> Logout</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                <?php endif; ?>

            </div>

        </nav>
        <br><br>
        <div class="container-fluid">

            <?php if (isset($notification)): ?>
                <div class="row">
                    <div class="col-md-4">

                    </div>
                    <div class="col-md-4">
                        <div class="alert alert-success" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <?php echo $notification; ?>
                        </div>
                    </div>
                    <div class="col-md-4">

                    </div>

                </div>
            <?php endif; ?>
            <?php echo $content ?>

            <hr style="text-shadow: 10px;">

            <footer>
                <p class="text-right" style="text-shadow: 10px;"><i>Version 1.0</i> - &copy; BeWhere 2015</p>
            </footer>
        </div> <!-- /container -->        


        <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
    </body>
</html>
