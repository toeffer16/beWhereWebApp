<nav>
    <ul class="nav masthead-nav">
        <li <?php if($page === "home"){ ?> class="active" <?php } ?> style="text-shadow:4px 4px 4px #0C090A;"><a href="<?php echo site_url("home") ?>">Home</a></li>
        <li <?php if($page === "features"){ ?> class="active" <?php } ?> style="text-shadow:4px 4px 4px #0C090A;"><a href="<?php echo site_url("home/features") ?>">Features</a></li>
        <li <?php if($page === "about"){ ?> class="active" <?php } ?> style="text-shadow:4px 4px 4px #0C090A;"><a href="<?php echo site_url("home/about") ?>">About</a></li>
    </ul>
</nav>