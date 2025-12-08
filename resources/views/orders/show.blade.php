<!DOCTYPE html>
<html lang="zxx" class="no-js">


<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="{{ asset('img/fav.png') }}">
    <meta charset="UTF-8">
    <title>Karma Shop - Order Details</title>
    <link rel="stylesheet" href="{{ asset('css/linearicons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/owl.carousel.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nouislider.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
</head>


<body>
    @include('layouts.header')


    <!-- Start Banner Area -->
    <section class="banner-area organic-breadcrumb">
        <div class="container">
            <div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
                <div class="col-first">
                    <h1>Order Details</h1>
                    <nav class="d-flex align-items-center">
                        <a href="{{ route('home') }}">Home<span class="lnr lnr-arrow-right"></span></a>
                        <a href="{{ route('orders.index') }}">My Orders<span class="lnr lnr-arrow-right"></span></a>
                        <a href="#">Order #{{ $order->order_id }}</a>
                    </nav>
                </div>
            </div>
        </div>
    </section>
    <!-- End Banner Area -->


    <!--================Order Details Area =================-->
    <section class="order_details section_gap">
        <div class="container">
            <h3 class="title_confirmation">Order #{{ $order->order_id }}</h3>
            <div class="row order_d_inner">
                <div class="col-lg-4">
                    <div class="details_item">
                        <h4>Order Info</h4>
                        <ul class="list">
                            <li><a href="#"><span>Order number</span> : {{ $order->order_id }}</a></li>
                            <li><a href="#"><span>Date</span> : {{ $order->created_at->format('d M Y') }}</a></li>
                            <li><a href="#"><span>Total</span> : Rp {{ number_format($order->total, 0, ',', '.') }}</a></li>
                            <li><a href="#"><span>Payment method</span> : {{ ucfirst($order->payment_method ?? 'Not specified') }}</a></li>
                            <li><a href="#"><span>Payment status</span> :
                                @if($order->payment_status == 'paid')
                                    <span class="badge badge-success">Paid</span>
                                @elseif($order->payment_status == 'failed')
                                    <span class="badge badge-danger">Failed</span>
                                @else
                                    <span class="badge badge-warning">Pending</span>
                                @endif
                            </a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="details_item">
                        <h4>Billing Address</h4>
                        <ul class="list">
                            <li><a href="#"><span>Name</span> : {{ $order->first_name }} {{ $order->last_name }}</a></li>
                            <li><a href="#"><span>Email</span> : {{ $order->email }}</a></li>
                            <li><a href="#"><span>Phone</span> : {{ $order->phone }}</a></li>
                            <li><a href="#"><span>Street</span> : {{ $order->shipping_address }}</a></li>
                            <li><a href="#"><span>City</span> : {{ $order->city }}</a></li>
                            <li><a href="#"><span>Postcode</span> : {{ $order->postcode }}</a></li>
                            <li><a href="#"><span>Country</span> : {{ $order->country ?? 'Indonesia' }}</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="details_item">
                        <h4>Shipping Address</h4>
                        <ul class="list">
                            <li><a href="#"><span>Street</span> : {{ $order->shipping_address }}</a></li>
                            <li><a href="#"><span>City</span> : {{ $order->city }}</a></li>
                            <li><a href="#"><span>Country</span> : {{ $order->country ?? 'Indonesia' }}</a></li>
                            <li><a href="#"><span>Postcode</span> : {{ $order->postcode }}</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="order_details_table">
                <h2>Order Details</h2>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Product</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($order->items as $item)
                            <tr>
                                <td>
                                    <p>{{ $item->product ? $item->product->product_name : 'Product Deleted (ID: ' . $item->product_id . ')' }}</p>
                                </td>
                                <td>
                                    <h5>x {{ $item->quantity }}</h5>
                                </td>
                                <td>
                                    <p>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center">
                                    <p>No items found in this order</p>
                                </td>
                            </tr>
                            @endforelse
                            <tr>
                                <td><h4>Subtotal</h4></td>
                                <td><h5></h5></td>
                                <td><p>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</p></td>
                            </tr>
                            <tr>
                                <td><h4>Shipping</h4></td>
                                <td><h5></h5></td>
                                <td><p>Flat rate: Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</p></td>
                            </tr>
                            <tr>
                                <td><h4>Total</h4></td>
                                <td><h5></h5></td>
                                <td><p>Rp {{ number_format($order->total, 0, ',', '.') }}</p></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('orders.index') }}" class="btn btn-secondary">Kembali ke Daftar Orders</a>
            </div>
        </div>
    </section>
    <!--================End Order Details Area =================-->


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
            </div>
        </div>
    </footer>
    <!-- End footer Area -->


    <script src="{{ asset('js/vendor/jquery-2.2.4.min.js') }}"></script>
    <script src="{{ asset('js/vendor/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
</body>


</html>
