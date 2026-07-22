<!-- Product Detail -->
<?php if (isset($_GET['detail'])) : ?>
    <div class="detail">
        <div class="row mt-4">
            <div class="col-12 col-lg-6" id="foto">
                <img class="img-detail" src="assets/img/fresh.png" alt="">
            </div>
            <div class="col-12 col-lg-6">
                <h2><b>Fresh Flowers</b></h2><br>
                <h6><i>123 Sold Out</i></h6>
                <h3>Rp 80.000 - 150.000</h3>
                <div class="row jenis">
                    <div class="col">
                        5pcs
                    </div>
                    <div class="col">
                        10pcs
                    </div>
                    <div class="col">
                        15pcs
                    </div>
                    <div class="col">
                        20pcs
                    </div>
                </div>
                <div class="d-flex">
                    <button class="btn btn-primary">-</button>
                    <h5 class="mx-3">2</h5>
                    <button class="btn btn-primary">+</button>
                    <button class="btn btn-primary mx-4"><i class="bi bi-cart"></i> Add</button>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <h4><b>Review About This Products</b></h4>
            <div class="col-6">
                <div class="card" style="width: 18rem;">
                    <img src="assets/img/fresh.png" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-person"></i> Nama User</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card’s content.</p>
                        <div class="d-flex"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card" style="width: 18rem;">
                    <img src="assets/img/fresh.png" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-person"></i> Nama User</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card’s content.</p>
                        <div class="d-flex"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="products mt-4 py-4">
            <h5 class="px-4"><b>Products Recomendation</b></h5>
            <div class="items mt-4 d-flex justify-content-center align-items-center">
                <div class="card">
                    <img src="assets/img/fresh.png" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h6 class="card-title">Fresh Flowers</h6>
                        <h4>Rp 120.000</h4>
                        <p>200 Terjual</p>
                        <a href="index.php?hal=products&detail" class="btn btn">Add To Cart</a>
                    </div>
                </div>
                <div class="card">
                    <img src="assets/img/fresh.png" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h6 class="card-title">Fresh Flowers</h6>
                        <h4>Rp 120.000</h4>
                        <p>200 Terjual</p>
                        <a href="index.php?hal=products&detail" class="btn btn">Add To Cart</a>
                    </div>
                </div>
                <div class="card">
                    <img src="assets/img/fresh.png" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h6 class="card-title">Fresh Flowers</h6>
                        <h4>Rp 120.000</h4>
                        <p>200 Terjual</p>
                        <a href="index.php?hal=products&detail" class="btn btn">Add To Cart</a>
                    </div>
                </div>
                <div class="card">
                    <img src="assets/img/fresh.png" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h6 class="card-title">Fresh Flowers</h6>
                        <h4>Rp 120.000</h4>
                        <p>200 Terjual</p>
                        <a href="index.php?hal=products&detail" class="btn btn">Add To Cart</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
<?php else : ?>
    <div class="products">
        <div class="row">
            <div class="col-12 col-lg-6 mt-4">
                <form action="" method="post">
                    <div class="input-group w-75" style="border-radius: 50px; overflow: hidden;">
                        <input type="text" class="form-control" name="search" placeholder="Search">
                        <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
                    </div>
                </form>
            </div>
            <div class="col-12 col-lg-6 mt-4">
                <div class="row">
                    <div class="col-6">
                        <button class="btn btn-primary">Filter By Categories <i class="bi bi-filter"></i></button>
                    </div>
                    <div class="col-6">
                        <button class="btn btn-primary">Filter By Price <i class="bi bi-filter"></i></button>
                    </div>
                </div>
            </div>
            <div class="items mt-4 d-flex justify-content-center align-items-center">
                <div class="card">
                    <img src="assets/img/fresh.png" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h6 class="card-title">Fresh Flowers</h6>
                        <h4>Rp 120.000</h4>
                        <p>200 Terjual</p>
                        <a href="index.php?hal=products&detail" class="btn btn">Add To Cart</a>
                    </div>
                </div>
                <div class="card">
                    <img src="assets/img/fresh.png" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h6 class="card-title">Fresh Flowers</h6>
                        <h4>Rp 120.000</h4>
                        <p>200 Terjual</p>
                        <a href="index.php?hal=products&detail" class="btn btn">Add To Cart</a>
                    </div>
                </div>
                <div class="card">
                    <img src="assets/img/fresh.png" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h6 class="card-title">Fresh Flowers</h6>
                        <h4>Rp 120.000</h4>
                        <p>200 Terjual</p>
                        <a href="index.php?hal=products&detail" class="btn btn">Add To Cart</a>
                    </div>
                </div>
                <div class="card">
                    <img src="assets/img/fresh.png" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h6 class="card-title">Fresh Flowers</h6>
                        <h4>Rp 120.000</h4>
                        <p>200 Terjual</p>
                        <a href="index.php?hal=products&detail" class="btn btn">Add To Cart</a>
                    </div>
                </div>
                <div class="card">
                    <img src="assets/img/fresh.png" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h6 class="card-title">Fresh Flowers</h6>
                        <h4>Rp 120.000</h4>
                        <p>200 Terjual</p>
                        <a href="index.php?hal=products&detail" class="btn btn">Add To Cart</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<style>
    .img-detail {
        width: 400px;
        height: 400px;
        background-color: rgba(103, 209, 50, 0.2);
        padding: 50px;
    }

    .detail h3 {
        font-weight: 800;
        color: red;
    }

    .detail .jenis .col {
        height: 30px;
        background: #d3d3d3;
        border-radius: 5px;
        text-align: center;
        margin: 10px;
        padding-top: 4px;
        font-weight: 600;
        font-size: 15px;
    }

    .products {
        background-color: rgba(103, 209, 50, 0.2);
        width: 100vw;
        position: relative;
        left: 50%;
        right: 50%;
        margin-left: -50vw;
        margin-right: -50vw;
        padding-left: 50px;
        padding-right: 50px;
    }

    .products .name {
        text-align: center;
        padding-top: 30px;
        font-weight: 700;
        letter-spacing: 5px;
        color: #67d132;
    }

    .products .items .card {
        border: none;
        width: 220px;
        border-radius: 20px;
        margin: auto;
    }

    .products .items .card img {
        padding: 10px;
        height: 200px;
        width: 200px;
    }

    .products .items .card-body {
        text-align: start;
    }

    .products .items .card-body h6 {
        font-weight: 600;
    }

    .products .items .card-body h4 {
        font-weight: 800;
        color: red;
    }

    .products .items .card-body p {
        font-size: 14px;
        color: gray;
    }

    .products a {
        background-color: #67d132;
        width: 100%;
        color: white;
        font-size: 14px;
        font-weight: 600;
    }
</style>