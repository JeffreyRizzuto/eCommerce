<div class="container">

    <div class="row">

        <?php
            //require_once ('models/left-nav.php');
            require_once("models/config.php");
            require_once("models/master_page.php");
        ?>

        <div class="col-md-10 col-md-offset-2">

            <div class="row carousel-holder">

                <div class="col-md-12">
                    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                            <!--
                            <li data-target="#carousel-example-generic" data-slide-to="1"></li>
                            <li data-target="#carousel-example-generic" data-slide-to="2"></li>
                            -->
                        </ol>
                        <div class="carousel-inner">
                            <div class="item active">
                                <img class="slide-image" src="../images/BookSale_banner.png" style="height: 80%; width: 100%;" alt="">
                            </div>
                            <!--
                            <div class="item">
                                <img class="slide-image" src="http://placehold.it/800x300" alt="">
                            </div>
                            <div class="item">
                                <img class="slide-image" src="http://placehold.it/800x300" alt="">
                            </div>
                            -->
                        </div>
                        <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
                            <span class="glyphicon glyphicon-chevron-left"></span>
                        </a>
                        <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
                            <span class="glyphicon glyphicon-chevron-right"></span>
                        </a>
                    </div>
                </div>

            </div>

            <div class="row">
                
                <?php
                    $booklist = getAllBooks();

                    foreach($booklist as $book)
                    {
                        createProdThumb($book);
                    }
                ?>
            </div>

        </div>

    </div>

</div>
