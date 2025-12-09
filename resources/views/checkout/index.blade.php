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
                    <h1>Checkout</h1>
                    <nav class="d-flex align-items-center">
                        <a href="/">Home<span class="lnr lnr-arrow-right"></span></a>
                        <a href="#">Checkout</a>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <!--================Checkout Area =================-->
    <section class="checkout_area section_gap">
        <div class="container">
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
           
            <div class="row">
                <div class="col-lg-8">
                    <h3>Billing Details
                        <small class="ml-2">
                            <a href="{{ route('profile.edit') }}" class="primary-btn-sm" style="font-size: 10px; padding: 4px 8px;">Edit Profil</a>
                        </small>
                    </h3>

                    <div class="row contact_form">
                        <div class="col-md-6 form-group">
                            <label>First name</label>
                            <p class="form-control-static"><strong>
                                @php
                                    $firstName = $user->customer?->first_name;
                                    if (empty($firstName) && !empty($user->name)) {
                                        $nameParts = explode(' ', $user->name, 2);
                                        $firstName = $nameParts[0] ?? $user->name;
                                    }
                                @endphp
                                {{ $firstName ?? 'N/A' }}
                            </strong></p>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Last name</label>
                            <p class="form-control-static"><strong>
                                @php
                                    $lastName = $user->customer?->last_name;
                                    if (empty($lastName) && !empty($user->name)) {
                                        $nameParts = explode(' ', trim($user->name), 2);
                                        $lastName = $nameParts[1] ?? '';
                                    }
                                    if (empty($lastName)) {
                                        $lastName = '-';
                                    }
                                @endphp
                                {{ $lastName }}
                            </strong></p>
                        </div>
                        <div class="col-md-12 form-group">
                            <label>Email Address</label>
                            <p class="form-control-static"><strong>{{ $user->email ?? 'N/A' }}</strong></p>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Phone number</label>
                            <p class="form-control-static"><strong>
                                @php
                                    $phoneNumber = $user->customer?->phone_number;
                                    if (empty($phoneNumber) && isset($user->address)) {
                                        $phoneNumber = $user->address->phone ?? null;
                                    }
                                    if (empty($phoneNumber)) {
                                        $phoneNumber = '-';
                                    }
                                @endphp
                                {{ $phoneNumber }}
                            </strong></p>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Country</label>
                            <p class="form-control-static"><strong>{{ $user->address?->country ?? 'Indonesia' }}</strong></p>
                        </div>
                        <div class="col-md-12 form-group">
                            <label>Shipping Address</label>
                            <p class="form-control-static"><strong>
                                @php
                                    $shippingAddress = $user->address?->address_line_01 ?? $user->customer?->shipping_address ?? null;
                                    if (empty($shippingAddress)) {
                                        $shippingAddress = 'Alamat belum diisi';
                                    }
                                @endphp
                                {{ $shippingAddress }}
                            </strong></p>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Town/City</label>
                            <p class="form-control-static"><strong>{{ $user->address?->town_city ?? 'N/A' }}</strong></p>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Postcode/ZIP</label>
                            <p class="form-control-static"><strong>{{ $user->address?->postcode_zip ?? 'N/A' }}</strong></p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="order_box">
                        <h2>Your Order</h2>
                        <ul class="list">
                            <li><a href="#">Product <span>Total</span></a></li>
                           
                            @forelse ($items as $item)
                            <li>
                                <a href="#">
                                    {{ $item['product']->product_name ?? 'Product' }}
                                    <span class="middle">x {{ $item['quantity'] }}</span>
                                    <span class="last">{{ 'Rp' . number_format($item['item_total'], 0, ',', '.') }}</span>
                                </a>
                            </li>
                            @empty
                            <li><a href="#">Keranjang <span>Kosong</span></a></li>
                            <li class="text-center mt-3">
                                <a href="{{ route('home') }}" class="btn btn-sm btn-primary">Kembali Belanja</a>
                            </li>
                            @endforelse

                        </ul>
                        <ul class="list list_2">
                            <li><a href="#">Subtotal <span>{{ 'Rp' . number_format($subtotal, 0, ',', '.') }}</span></a></li>
                            <li><a href="#">Shipping <span>Flat rate: {{ 'Rp' . number_format($shipping, 0, ',', '.') }}</span></a></li>
                            <li><a href="#">Total <span>{{ 'Rp' . number_format($total, 0, ',', '.') }}</span></a></li>
                        </ul>

                        @if(count($items) > 0)
                        {{-- FORM CHECKOUT --}}
                        <form method="POST" action="{{ route('checkout.store') }}" id="checkout-form">
                            @csrf
                            
                            {{-- Hidden inputs untuk data user --}}
                            @php
                                $firstName = $user->customer?->first_name;
                                if (empty($firstName) && !empty($user->name)) {
                                    $nameParts = explode(' ', trim($user->name), 2);
                                    $firstName = $nameParts[0] ?? $user->name;
                                }
                                
                                $lastName = $user->customer?->last_name;
                                if (empty($lastName) && !empty($user->name)) {
                                    $nameParts = explode(' ', trim($user->name), 2);
                                    $lastName = $nameParts[1] ?? '';
                                }
                                
                                $phoneNumber = $user->customer?->phone_number ?? '';
                                $shippingAddress = $user->address?->address_line_01 ?? $user->customer?->shipping_address ?? '';
                            @endphp
                            
                            <input type="hidden" name="first_name" value="{{ $firstName ?? '' }}">
                            <input type="hidden" name="last_name" value="{{ $lastName ?? '' }}">
                            <input type="hidden" name="email" value="{{ $user->email ?? '' }}">
                            <input type="hidden" name="phone" value="{{ $phoneNumber }}">
                            <input type="hidden" name="shipping_address" value="{{ $shippingAddress }}">
                            <input type="hidden" name="city" value="{{ $user->address?->town_city ?? '' }}">
                            <input type="hidden" name="postcode" value="{{ $user->address?->postcode_zip ?? '' }}">
                            <input type="hidden" name="country" value="{{ $user->address?->country ?? 'Indonesia' }}">
                            
                            <div class="payment_item">
                                <div class="radion_btn">
                                    <input type="radio" id="f-option5" name="payment_method" value="check_payment">
                                    <label for="f-option5">Check payments</label>
                                    <div class="check"></div>
                                </div>
                                <p>Please send a check to Store Name, Store Street, Store Town, Store State / County,
                                    Store Postcode.</p>
                            </div>
                           
                            <div class="payment_item active">
                                <div class="radion_btn">
                                    <input type="radio" id="f-option6" name="payment_method" value="paypal" checked>
                                    <label for="f-option6">Paypal </label>
                                    <img src="{{ asset('img/product/card.jpg') }}" alt="">
                                    <div class="check"></div>
                                </div>
                                <p>Pay via PayPal; you can pay with your credit card if you don't have a PayPal
                                    account.</p>
                            </div>
                           
                            <div class="creat_account">
                                <input type="checkbox" id="f-option4" name="terms" value="1" required>
                                <label for="f-option4">I've read and accept the </label>
                                <a href="#">terms & conditions*</a>
                            </div>
                           
                            <button type="submit" class="primary-btn">Proceed to Paypal</button>
                        </form>
                        
                        @else
                        <div class="alert alert-warning">
                            <p>Keranjang Anda kosong. Silakan tambahkan produk terlebih dahulu.</p>
                            <a href="{{ route('home') }}" class="btn btn-primary">Kembali Belanja</a>
                        </div>
                        @endif
                       
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--================End Checkout Area =================-->

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

                            <form target="_blank" novalidate="true" action="https://spondonit.us12.list-manage.com/subscribe/post?u=1462626880ade1ac87bd9c93a&amp;id=92a4423d01"
                                method="get" class="form-inline">

                                <div class="d-flex flex-row">

                                    <input class="form-control" name="EMAIL" placeholder="Enter Email" onfocus="this.placeholder = ''"
                                        onblur="this.placeholder = 'Enter Email '" required="" type="email">

                                    <button class="click-btn btn btn-default"><i class="fa fa-long-arrow-right"
                                            aria-hidden="true"></i></button>
                                    <div style="position: absolute; left: -5000px;">
                                        <input name="b_36c4fd991d266f23781ded980_aefe40901a" tabindex="-1" value=""
                                            type="text">
                                    </div>
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
                <p class="footer-text m-0"><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | This template is made with <i class="fa fa-heart-o" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
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
</body>

</html>