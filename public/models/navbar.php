<div class="navbar navbar-inverse navbar-static-top" role="navigation">

    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" rel="home" href="/" title="Store - Homepage">Store</a>
    </div>

    <div class="collapse navbar-collapse navbar-ex1-collapse">

        <ul class="nav navbar-nav pull-right">
            <?php
            if(isUserLoggedIn()){
                echo "
                <li><a href='account.php'>Account</a></li>
                <li><a href='cart.php'>Cart</a></li>
                ";
            }
            else{
                echo"
                <li><a href='login.php'>Login</a></li>
                <li><a href='register.php'>Register</a></li>
                <li><a href='cart.php'>Cart</a></li>
                ";
            }
            ?>
        </ul>

        <div class="col-sm-3 col-md-3 center-block">
            <form class="navbar-form" role="search">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search" name="srch-term" id="srch-term">
                    <div class="input-group-btn">
                        <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>