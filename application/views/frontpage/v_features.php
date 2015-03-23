
<style>
    .carousel-inner > .item > img,
    .carousel-inner > .item > a > img {
        width: 100%;
        height: 100%;
        margin: auto;
    }
</style>

<div class="row">
    <div class="col-md-1">
    </div>

    <div class="col-md-offset-1">
        <div class="inner cover" style="padding-left: -100px;">
            <div id="myCarousel" class="carousel slide center-block" data-ride="carousel" style="width: 700px; height: 390px;" >
                <!-- Indicators -->
                <ol class="carousel-indicators">
                    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                    <li data-target="#myCarousel" data-slide-to="1"></li>
                    <li data-target="#myCarousel" data-slide-to="2"></li>
                </ol>

                <!-- Wrapper for slides -->
                <div class="carousel-inner" role="listbox">
                    <div class="item active">
                        <img src="<?php echo base_url("img/first.png"); ?>"  alt="Locate me" width="900" height="700">
                    </div>

                    <div class="item">
                        <img src="<?php echo base_url("img/second.png"); ?>" alt="Survey the area" width="900" height="700">
                    </div>

                    <div class="item">
                        <img src="<?php echo base_url("img/third.png"); ?>" alt="Exit Route" width="900" height="700">
                    </div>
                </div>

                <!-- Left and right controls -->
                <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-1">
    </div>
</div>
