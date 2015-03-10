<style>
    .input-group-addon {
        background-color: #ECF0F1;
    }
    .panel {
        margin-bottom: 20px;
        background-color: #ffffff;
        border: 1px solid transparent;
        border-radius: 4px;
        box-shadow: 0px 1px 1px rgba(0, 0, 0, 0.05);
    }
</style>

<div class="row">
    <div class="col-sm-4"></div>
    <div class="col-sm-4">
        <div class="panel panel-success">
            <div class="panel-heading">Administrator Log-in</div>
            <h3 style="text-align: center; "> Log-in </h3>
            <div class="panel-body">
                <form class="form-horizontal" action="<?php echo site_url("administrator/verify_login"); ?>" method="POST">

                    <div class="form-group">
                        <div class="col-sm-1"></div>

                        <div class="col-sm-10">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-addon"><i class="fa fa-user fa-lg"></i></div>
                                            <input type="text" class="form-control" name="username" id="inputEmail3" placeholder="Username">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-addon"><i class="fa fa-lock fa-lg"></i></div>
                                            <input type="password" class="form-control" name="password" id="inputPassword3" placeholder="Password">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-0 col-sm-10">
                                        <button type="submit" class="btn btn-default"> Sign in</button> &nbsp;
                                        <!--<button class="btn btn-info"><a href="<?php echo site_url("") ?>"><i style="color: white;" class="fa fa-home fa-lg"></i></a></button> -->
                                        <a class="btn btn-info" href="<?php echo site_url("") ?>"><i style="color: white;" class="fa fa-home fa-lg"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-1"></div>

                    </div>
                </form> 
            </div>
        </div>
    </div>

</div>

<div class="col-sm-4"></div>
