<!DOCTYPE html>
<html lang="zxx" class="no-js">


<head>
    <!-- Mobile Specific Meta -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Favicon-->
    <link rel="shortcut icon" href="img/fav.png">
    <!-- Author Meta -->
    <meta name="author" content="CodePixar">
    <!-- Meta Description -->
    <meta name="description" content="">
    <!-- Meta Keyword -->
    <meta name="keywords" content="">
    <!-- meta character set -->
    <meta charset="UTF-8">
    <!-- Site Title -->
    <title>Karma Shop</title>


    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/linearicons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/owl.carousel.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nouislider.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ion.rangeSlider.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/ion.rangeSlider.skinFlat.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">


</head>


<body>


    <!-- Start Area -->


    @include('layouts.header')
    <section class="banner-area organic-breadcrumb">
        <div class="container">
            <div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
                <div class="col-first">
                    <h1>Shopping Cart</h1>
                    <nav class="d-flex align-items-center">
                        <a href="/dashboard">Home<span class="lnr lnr-arrow-right"></span></a>
                        <a href="#">Cart</a>
                    </nav>
                </div>
            </div>
        </div>
    </section>


    <section class="cart_area">
        <div class="container">
            <div class="cart_inner">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Product</th>
                                <th scope="col">Price</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $item)
                                <tr>
                                    <td>
                                        <div class="media align-items-center">
                                            <img src="{{ $item['product']->product_image
                                                ? asset($item['product']->product_image)
                                                : 'https://via.placeholder.com/100x100?text=No+Image' }}"
                                                alt="{{ $item['product']->product_name }}" class="mr-3"
                                                style="width: 100px;">
                                            <div class="media-body">
                                                <p class="mb-0">{{ $item['product']->product_name }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <h5 class="mb-0">Rp{{ number_format($item['unit_cost'], 0, ',', '.') }}</h5>
                                    </td>
                                    <td>
                                        <div class="product_count d-flex align-items-center">
                                            <button type="button" class="increase items-count"
                                                onclick="changeQty(this, 1)">
                                                <i class="lnr lnr-chevron-up"></i>
                                            </button>


                                            <input type="number" value="{{ $item['quantity'] }}" min="0"
                                                class="input-text qty mx-2 text-center" style="width: 60px;"
                                                data-product="{{ $item['product']->product_id }}"
                                                data-price="{{ $item['unit_cost'] }}" />


                                            <button type="button" class="reduced items-count"
                                                onclick="changeQty(this, -1)">
                                                <i class="lnr lnr-chevron-down"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td>
                                        <h5 class="mb-0 item-total">
                                            Rp{{ number_format($item['item_total'], 0, ',', '.') }}
                                        </h5>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Keranjang kosong</td>
                                </tr>
                            @endforelse


                            <tr>
                                <td colspan="2"></td>
                                <td>
                                    <h5>Subtotal</h5>
                                </td>
                                <td>
                                    <h5 id="subtotal">Rp{{ number_format($subtotal, 0, ',', '.') }}</h5>
                                </td>
                            </tr>


                            <tr class="shipping_area">
                                <td colspan="2"></td>
                                <td>
                                    <h5>Shipping</h5>
                                </td>
                                <td>
                                    <div class="shipping_box">
                                        <ul class="list shipping-options mb-3">
                                            <li class="mb-2"><a href="#" data-cost="5000">Flat Rate: Rp5.000</a>
                                            </li>
                                            <li class="mb-2"><a href="#" data-cost="10000">Flat Rate:
                                                    Rp10.000</a></li>
                                            <li class="mb-2"><a href="#" data-cost="2000">Local Delivery:
                                                    Rp2.000</a></li>
                                        </ul>


                                        <div class="mt-3">
                                            <h6>Calculate Shipping <i class="fa fa-caret-down"></i></h6>
                                            <h5 id="shipping-cost" class="mb-2">Rp0</h5>
                                            <h5 id="grand-total" class="mt-2">
                                                Rp{{ number_format($subtotal, 0, ',', '.') }}
                                            </h5>
                                        </div>
                                    </div>
                                </td>
                            </tr>


                            <tr class="out_button_area">
                                <td colspan="4">
                                    <div class="checkout_btn_inner d-flex justify-content-end align-items-center">
                                        <a class="gray_btn mr-2" href="{{ route('home') }}">Continue
                                            Shopping</a>
                                        <a href="{{ route('checkout.index') }}" class="primary-btn">Proceed to checkout</a>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>


    <meta name="csrf-token" content="{{ csrf_token() }}">


    <!--================End Cart Area =================-->


    <!-- start footer Area -->
    <footer class="footer-area section_gap">
        <div class="container">
            <div class="row">
                <div class="col-lg-3  col-md-6 col-sm-6">
                    <div class="single-footer-widget">
                        <h6>About Us</h6>
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt
                            ut labore dolore
                            magna aliqua.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4  col-md-6 col-sm-6">
                    <div class="single-footer-widget">
                        <h6>Newsletter</h6>
                        <p>Stay update with our latest</p>
                        <div class="" id="mc_embed_signup">


                            <form target="_blank" novalidate="true"
                                action="https://spondonit.us12.list-manage.com/subscribe/post?u=1462626880ade1ac87bd9c93a&amp;id=92a4423d01"
                                method="get" class="form-inline">


                                <div class="d-flex flex-row">


                                    <input class="form-control" name="EMAIL" placeholder="Enter Email"
                                        onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter Email '"
                                        required="" type="email">




                                    <button class="click-btn btn btn-default"><i class="fa fa-long-arrow-right"
                                            aria-hidden="true"></i></button>
                                    <div style="position: absolute; left: -5000px;">
                                        <input name="b_36c4fd991d266f23781ded980_aefe40901a" tabindex="-1"
                                            value="" type="text">
                                    </div>


                                    <!-- <div class="col-lg-4 col-md-4">
             <button class="bb-btn btn"><span class="lnr lnr-arrow-right"></span></button>
            </div>  -->
                                </div>
                                <div class="info"></div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3  col-md-6 col-sm-6">
                    <div class="single-footer-widget mail-chimp">
                        <h6 class="mb-20">Instragram Feed</h6>
                        <ul class="instafeed d-flex flex-wrap">
                            <li><img src="img/i1.jpg" alt=""></li>
                            <li><img src="img/i2.jpg" alt=""></li>
                            <li><img src="img/i3.jpg" alt=""></li>
                            <li><img src="img/i4.jpg" alt=""></li>
                            <li><img src="img/i5.jpg" alt=""></li>
                            <li><img src="img/i6.jpg" alt=""></li>
                            <li><img src="img/i7.jpg" alt=""></li>
                            <li><img src="img/i8.jpg" alt=""></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-sm-6">
                    <div class="single-footer-widget">
                        <h6>Follow Us</h6>
                        <p>Let us be social</p>
                        <div class="footer-social d-flex align-items-center">
                            <a href="#"><i class="fa fa-facebook"></i></a>
                            <a href="#"><i class="fa fa-twitter"></i></a>
                            <a href="#"><i class="fa fa-dribbble"></i></a>
                            <a href="#"><i class="fa fa-behance"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-bottom d-flex justify-content-center align-items-center flex-wrap">
                <p class="footer-text m-0">
                    <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                    Copyright &copy;
                    <script>
                        document.write(new Date().getFullYear());
                    </script> All rights reserved | This template is made with <i class="fa fa-heart-o"
                        aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
                    <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                </p>
            </div>
        </div>
    </footer>
    <!-- End footer Area -->


    <!-- End Area -->


    <script src="{{ asset('js/vendor/jquery-2.2.4.min.js') }}"></script>
    <script src="{{ asset('js/vendor/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/jquery.ajaxchimp.min.js') }}"></script>
    <script src="{{ asset('js/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('js/jquery.sticky.js') }}"></script>
    <script src="{{ asset('js/nouislider.min.js') }}"></script>
    <script src="{{ asset('js/countdown.js') }}"></script>
    <script src="{{ asset('js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('js/gmaps.min.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>


    <script>
        function changeQty(button, delta) {
            const input = button.parentElement.querySelector('.qty');
            let value = parseInt(input.value) || 0;
            value += delta;
            if (value < 0) value = 0;
            input.value = value;


            updateCart(input.dataset.product, value);
            updateTotals();
        }


        function updateCart(productId, quantity) {
            fetch("{{ route('cart.update') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: quantity
                    })
                })
                .then(res => res.json())
                .then(() => {
                    if (quantity < 1) {
                        const row = document.querySelector('[data-product="' + productId + '"]').closest('tr');
                        row.remove();
                        updateTotals();
                    }
                })
                .catch(err => console.error("Cart update error:", err));
        }


        function updateTotals() {
            let subtotal = 0;
            document.querySelectorAll('.qty').forEach(input => {
                const price = parseInt(input.dataset.price);
                const quantity = parseInt(input.value);
                const row = input.closest('tr');
                const totalCell = row.querySelector('.item-total');


                if (quantity > 0) {
                    const total = price * quantity;
                    totalCell.textContent = "Rp" + total.toLocaleString('id-ID');
                    subtotal += total;
                }
            });


            document.querySelector('#subtotal').textContent = "Rp" + subtotal.toLocaleString('id-ID');


            const shippingCost = parseInt(document.getElementById('shipping-cost').textContent.replace(/\D/g, '')) || 0;
            document.getElementById('grand-total').textContent = "Rp" + (subtotal + shippingCost).toLocaleString('id-ID');
        }


        // shipping option click
        document.addEventListener('DOMContentLoaded', function() {
            updateTotals();


            const shippingOptions = document.querySelectorAll('.shipping-options li a');
            const shippingCostCell = document.getElementById('shipping-cost');
            const grandTotalCell = document.getElementById('grand-total');


            shippingOptions.forEach(option => {
                option.addEventListener('click', function(e) {
                    e.preventDefault();
                    const cost = parseInt(this.dataset.cost);
                    shippingCostCell.textContent = "Rp" + cost.toLocaleString('id-ID');


                    let subtotal = parseInt(document.querySelector('#subtotal').textContent.replace(
                        /\D/g, '')) || 0;
                    const grandTotal = subtotal + cost;
                    grandTotalCell.textContent = "Rp" + grandTotal.toLocaleString('id-ID');


                    shippingOptions.forEach(o => o.parentElement.classList.remove('active'));
                    this.parentElement.classList.add('active');
                });
            });
        });
    </script>


</body>


</html>
