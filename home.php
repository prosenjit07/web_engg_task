<!-- Header-->

<!-- use pic for home page -->
<!-- <header class="bg-dark py-5" id="main-header"> -->

<header class="bg-dark py-2">
    <div class="container px-2 px-lg-3 my-2">
        <div class="text-center text-white">
            <!-- <h1 class="display-4 fw-bolder"></h1> -->
            <h4> Wellcome to BooksellShop</h4>
            <!-- <p class="lead fw-normal text-white-50 mb-0">☎️হটলাইন:+8801960230293</p><br> -->
        </div>
        <div>
        </div>

    </div>
</header>

        

                <!-- <div class="card-deck">
            <div class="card">
                <img class="" src="images/prosenjit.jpg" alt="Customer Photo">
                <div class="card-body">
                    <h5 class="card-title">Card title</h5>
                    <p class="card-text">This is a wider card with supporting text below as a natural lead-in to
                        additional content. This content is a little bit longer.</p>
                </div>

            </div>
            <div class="card">
                <img src="..." class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">Card title</h5>
                    <p class="card-text">This card has supporting text below as a natural lead-in to additional
                        content.</p>
                </div>

            </div>
            <div class="card">
                <img src="..." class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">Card title</h5>
                    <p class="card-text">This is a wider card with supporting text below as a natural lead-in to
                        additional content. This card has even longer content than the first to show that equal height
                        action.</p>
                </div>

            </div>
        </div> -->

               
            <!-- Bootstrap JS (Optional, for certain functionalities) -->
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        </body>

        </html>

        <style>
        .book-cover {
            object-fit: contain !important;
            height: auto !important;
        }
        </style>
        <section class="py-5">
            <div class="container px-4 px-lg-5 mt-5">
                <div class="row gx-4 gx-lg-5 row-cols-md-3 row-cols-xl-4 justify-content-center">
                    <?php
          $products = $conn->query("SELECT * FROM `products` where status = 1 order by rand() limit 8 ");
          while ($row = $products->fetch_assoc()):
            $upload_path = base_app . '/uploads/product_' . $row['id'];
            $img = "";
            if (is_dir($upload_path)) {
              $fileO = scandir($upload_path);
              if (isset($fileO[2]))
                $img = "uploads/product_" . $row['id'] . "/" . $fileO[2];
              // var_dump($fileO);
            }
            foreach ($row as $k => $v) {
              $row[$k] = trim(stripslashes($v));
            }
            $inventory = $conn->query("SELECT * FROM inventory where product_id = " . $row['id']);
            $inv = array();
            while ($ir = $inventory->fetch_assoc()) {
              $inv[] = number_format($ir['price']);
            }
            ?>
                    <div class="col mb-5">
                        <div class="card product-item">
                            <!-- Product image-->
                            <img class="card-img-top w-100 book-cover" src="<?php echo validate_image($img) ?>"
                                alt="..." />
                            <!-- Product details-->
                            <div class="card-body p-4">
                                <div class="">
                                    <!-- Product name-->
                                    <h5 class="fw-bolder">
                                        <?php echo $row['title'] ?>
                                    </h5>
                                    <!-- Product price-->
                                    <?php foreach ($inv as $k => $v): ?>
                                    <span><b>Price: </b>
                                        <?php echo $v ?>
                                    </span>
                                    <?php endforeach; ?>
                                </div>
                                <p class="m-0"><small>By:
                                        <?php echo $row['author'] ?>
                                    </small></p>
                            </div>
                            <!-- Product actions-->
                            <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                                <div class="text-center">
                                    <a class="btn btn-flat btn-primary "
                                        href=".?p=view_product&id=<?php echo md5($row['id']) ?>">View</a>
                                </div>

                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </section>
        <script>
        var inv = $.parseJSON('<?php echo json_encode($inv) ?>');
        $(function() {
            $('#add-cart').submit(function(e) {
                e.preventDefault();
                if ('<?php echo $_settings->userdata('id') ?>' <= 0) {
                    uni_modal("", "login.php");
                    return false;
                }
                start_loader();
                $.ajax({
                    url: 'classes/Master.php?f=add_to_cart',
                    data: $(this).serialize(),
                    method: 'POST',
                    dataType: "json",
                    error: err => {
                        console.log(err)
                        alert_toast("an error occured", 'error')
                        end_loader()
                    },
                    success: function(resp) {
                        if (typeof resp == 'object' && resp.status == 'success') {
                            $('#cart-count').text(resp.cart_count)
                            window.location.href = './?p=checkout';
                        } else {
                            console.log(resp)
                            alert_toast("an error occured", 'error')
                        }
                        end_loader();
                    }
                })
            })
        })
        </script>