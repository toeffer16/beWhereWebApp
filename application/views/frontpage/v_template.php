<!DOCTYPE html>

<html class="no-js" lang="">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>BeWhere</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" href="<?php echo base_url("img/favicon.png") ?>">


        <!-- Bootstrap core CSS -->
        <link rel="stylesheet"  href="<?php echo base_url("res/Bootstrapfront/bootstrap.css") ?> "/> 
        <link rel="stylesheet"  href="<?php echo base_url("res/Bootstrapfront/bootstrap.min.css") ?> "/> 

        <!-- Custom styles for this template -->
        <link rel="stylesheet" href="<?php echo base_url("res/Bootstrapfront/cover.css") ?>"/>

        <script src="<?php echo base_url("res/js/vendor/modernizr-2.8.3-respond-1.4.2.min.js") ?>"></script>

        <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
        <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
        <script src="<?php echo base_url("res/Bootstrapfront/ie-emulation-modes-warning.js") ?>"></script>


        <style>
            body {
                padding-top: 50px;
                padding-bottom: 20px;        
                background: url('<?php echo base_url("img/bg2.png"); ?>') no-repeat center center fixed; 
                -webkit-background-size: cover;
                -moz-background-size: cover;
                -o-background-size: cover;
                background-size: cover;
            }

        </style>

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>

    <body>

        <div class="site-wrapper">

            <div class="site-wrapper-inner">

                <div class="cover-container">

                    <div class="masthead clearfix">
                        <div class="inner">
                            <h3 class="masthead-brand"><a href="<?php echo site_url("") ?>"><img alt="BeWhere" src="<?php echo base_url("img/logo-green1.png") ?>" class="img-responsive" style="height: 80px; width: 415px; margin-left: -100px;"/></h3></a>
                            <?php echo $nav ?>
                        </div>
                    </div>


                    <?php echo $content ?>

                    <div class="mastfoot">
                        <div class="inner">
                            <p style="text-shadow:4px 4px 4px #0C090A; color:white;">&copy; BeWhere 2015</p>
                        </div>
                    </div>

                </div>

            </div>

        </div>

        <!-- Bootstrap core JavaScript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="res/Bootstrapfront/jquery.js"></script>
        <script src="res/Bootstrapfront/bootstrap.js"></script>
        <script src="res/Bootstrapfront/docs.js"></script>
        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="res/Bootstrapfront/ie10-viewport-bug-workaround.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>


    </body>
</html>