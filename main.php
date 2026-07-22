<section class="hero" id="hero">
    <img class="mb-4" src="assets/img/hero.png" alt="">
    <div class="text mb-4">
        <h2>Fresh Flowers</h2>
        <h4>Natural & Pretty Flowers</h4>
        <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Qui aliquam omnis, impedit consequatur voluptates natus voluptate cumque et quisquam provident laborum blanditiis quia recusandae corrupti? Doloremque dolor odio.</p>
    </div>
</section>
<section class="products" id="products">
    <div class="categories">
        <h5>Categories</h5>
        <div class="items mt-4">
            <div class="item">
                <img src="assets/img/fresh.png" alt="">
                <p>Fresh Flowers</p>
            </div>
            <div class="item">
                <img src="assets/img/dried.png" alt="">
                <p>Dried Flowers</p>
            </div>
            <div class="item">
                <img src="assets/img/plant.png" alt="">
                <p>Fresh Plants</p>
            </div>
            <div class="item">
                <img src="assets/img/wreath.png" alt="">
                <p>Flowers Wreath</p>
            </div>
            <div class="item">
                <img src="assets/img/tools.png" alt="">
                <p>Farming Tools</p>
            </div>
        </div>
    </div>
    <div class="products-row">
        <h5 class="name">Our Products</h5>
        <div class="items mt-4 d-flex justify-content-center align-items-center">
            <div class="card">
                <img src="assets/img/fresh.png" class="card-img-top" alt="...">
                <div class="card-body">
                    <h6 class="card-title">Fresh Flowers</h6>
                    <h4>Rp 120.000</h4>
                    <p>200 Terjual</p>
                    <a href="#" class="btn btn">Add To Cart</a>
                </div>
            </div>
            <div class="card">
                <img src="assets/img/fresh.png" class="card-img-top" alt="...">
                <div class="card-body">
                    <h6 class="card-title">Fresh Flowers</h6>
                    <h4>Rp 120.000</h4>
                    <p>200 Terjual</p>
                    <a href="#" class="btn btn">Add To Cart</a>
                </div>
            </div>
            <div class="card">
                <img src="assets/img/fresh.png" class="card-img-top" alt="...">
                <div class="card-body">
                    <h6 class="card-title">Fresh Flowers</h6>
                    <h4>Rp 120.000</h4>
                    <p>200 Terjual</p>
                    <a href="#" class="btn btn">Add To Cart</a>
                </div>
            </div>
            <div class="card">
                <img src="assets/img/fresh.png" class="card-img-top" alt="...">
                <div class="card-body">
                    <h6 class="card-title">Fresh Flowers</h6>
                    <h4>Rp 120.000</h4>
                    <p>200 Terjual</p>
                    <a href="#" class="btn btn">Add To Cart</a>
                </div>
            </div>
        </div>
        <a href="#" class="btn btn mt-4 mb-5">Show All</a>
    </div>
</section>
<section class="services" id="services">
    <div class="servis row">
        <div class="col-3">
            <i class="bi bi-trophy"></i>
            <h6>Best Products</h6>
        </div>
        <div class="col-3">
            <i class="bi bi-stopwatch"></i>
            <h6>Fast Respon</h6>
        </div>
        <div class="col-3">
            <i class="bi bi-truck"></i>
            <h6>Free Shipping</h6>
        </div>
        <div class="col-3">
            <i class="bi bi-cash-coin"></i>
            <h6>Easy Payment</h6>
        </div>
    </div>
</section>
<section class="contact" id="contact">
    <h5>Contact Us</h5>
    <div class="row">
        <div class="col-6">
            <form action="">
                <label for="" class="form-label">Your Name :</label>
                <input type="text" class="form-control mb-4" name="name">
                <label for="" class="form-label">Your Email :</label>
                <input type="email" class="form-control mb-4" name="email">
                <label for="" class="form-label">Your Message :</label>
                <textarea class="form-control mb-4" name="message" rows="4"></textarea>
                <button class="btn btn" name="send"><i class="bi bi-send-fill"></i>Send!</button>
            </form>
            <div class="social row mt-5">
                <div class="col-6">
                    <h3><i class="bi bi-instagram"></i> @florist.co</h3>
                </div>
                <div class="col-6">
                    <h3><i class="bi bi-facebook"></i> @florist.co</h3>
                </div>
            </div>
        </div>
        <div class="col-6">
            <img src="assets/img/contact.png" alt="">
        </div>
    </div>
</section>

<style>  
.hero {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

.hero img {
  width: 85%;
  display: block;
  margin-left: auto;
  margin-right: auto;
  background-size: cover;
  background-position: center;
  text-align: center;
}

.hero .text {
  text-align: center;
}

.hero h2 {
  font-weight: 600;
}

.hero h4 {
  letter-spacing: 8px;
}

.hero p {
  max-width: 900px;
}

.categories {
  background-color: #67d132;
  width: 100vw;
  position: relative;
  left: 50%;
  right: 50%;
  margin-left: -50vw;
  margin-right: -50vw;
  padding-left: 50px;
  padding-right: 50px;
}

.categories h5 {
  color: white;
  text-align: center;
  padding-top: 30px;
  font-weight: 700;
  letter-spacing: 5px;
}

.categories .items {
  display: flex;
  max-width: 100%;
  justify-content: space-between;
  flex-wrap: wrap;
}

.categories .item {
  text-align: center;
  flex-basis: 18%;
  color: white;
  font-weight: 500;
}

.categories .item img {
  margin-bottom: 10px;
  width: 150px;
  height: 150px;
}

.products-row {
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

.products-row .name {
  text-align: center;
  padding-top: 30px;
  font-weight: 700;
  letter-spacing: 5px;
  color: #67d132;
}

.products-row .items .card {
  border: none;
  width: 220px;
  border-radius: 20px;
  margin: auto;
}

.products-row .items .card img {
  padding: 10px;
  height: 200px;
  width: 200px;
}

.products-row .items .card-body {
  text-align: start;
}

.products-row .items .card-body h6 {
  font-weight: 600;
}

.products-row .items .card-body h4 {
  font-weight: 800;
  color: red;
}

.products-row .items .card-body p {
  font-size: 14px;
  color: gray;
}

.products-row a {
  background-color: #67d132;
  width: 100%;
  color: white;
  font-size: 14px;
  font-weight: 600;
}

.products-row {
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

.products-row .name {
  text-align: center;
  padding-top: 30px;
  font-weight: 700;
  letter-spacing: 5px;
  color: #67d132;
}

.products-row .items .card {
  border: none;
  width: 220px;
  border-radius: 20px;
  margin: auto;
}

.products-row .items .card img {
  padding: 10px;
  height: 200px;
  width: 200px;
}

.products-row .items .card-body {
  text-align: start;
}

.products-row .items .card-body h6 {
  font-weight: 600;
}

.products-row .items .card-body h4 {
  font-weight: 800;
  color: red;
}

.products-row .items .card-body p {
  font-size: 14px;
  color: gray;
}

.products-row a {
  background-color: #67d132;
  width: 100%;
  color: white;
  font-size: 14px;
  font-weight: 600;
}

.services {
  background-color: #67d132;
  width: 100vw;
  position: relative;
  left: 50%;
  right: 50%;
  margin-left: -50vw;
  margin-right: -50vw;
  padding-left: 50px;
  padding-right: 50px;
  padding-top: 20px;
  padding-bottom: 20px;
}

.services .col-3 {
  text-align: center;
  color: white;
}

.services .col-3 h6 {
  font-weight: 500;
  margin-top: -10px;
  font-size: 18px;
}

.services i {
  font-size: 80px;
}

.contact h5 {
  color: #67d132;
  text-align: center;
  padding-top: 30px;
  font-weight: 700;
  letter-spacing: 5px;
  margin-bottom: 20px;
}

.contact label {
  color: #67d132;
  font-weight: 500;
}

.contact button {
  background-color: #67d132;
  width: 100%;
  color: white;
  font-size: 14px;
  font-weight: 600;
}

.contact .social i {
  color: #67d132;
  font-size: 30px;
  margin-right: 10px;
}
.contact h3 {
  color: #67d132;
  font-size: 17px;
}

.contact input,
.contact textarea {
  background-color: rgba(103, 209, 50, 0.2);
  border-radius: 5px;
}

.contact img {
  width: 100%;
  display: flex;
  justify-content: end;
  align-items: end;
}

</style>