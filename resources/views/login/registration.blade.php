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


    <!--================Registration Box Area =================-->
    @extends('layouts.loginlayouts')


    @section('content')
        <section class="login_box_area section_gap">
            <div class="container">
                <div class="row">
                    <!-- Bagian kiri -->
                    <div class="col-lg-6">
                        <div class="login_box_img">
                            <img class="img-fluid" src="{{ asset('img/login.jpg') }}" alt="">
                            <div class="hover">
                                <h4>Have account?</h4>
                                <p>There are advances being made in science and technology everyday, and a good example of
                                    this is the</p>
                                <a class="primary-btn" href="/login">Login</a>
                            </div>
                        </div>
                    </div>


                    <!-- Bagian kanan -->
                    <div class="col-lg-6">
                        <div class="login_form_inner">
                            <h3>registration</h3>


                            {{-- Pesan sukses --}}
                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif


                            {{-- Pesan error --}}
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif


                            <form class="row login_form" action="{{ route('register') }}" method="POST" id="contactForm"
                                novalidate="novalidate">
                                @csrf


                                {{-- Field Nama Depan --}}
                                <div class="col-md-12 form-group">
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                        id="first_name" name="first_name" placeholder="First Name"
                                        onfocus="this.placeholder = ''" onblur="this.placeholder = 'First Name'"
                                        value="{{ old('first_name') }}" required>
                                    @error('first_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>


                                {{-- Field Nama Belakang --}}
                                <div class="col-md-12 form-group">
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                        id="last_name" name="last_name" placeholder="Last Name"
                                        onfocus="this.placeholder = ''" onblur="this.placeholder = 'Last Name'"
                                        value="{{ old('last_name') }}" required>
                                    @error('last_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>


                                {{-- Field Email (Untuk Login) --}}
                                <div class="col-md-12 form-group">
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" placeholder="Email" onfocus="this.placeholder = ''"
                                        onblur="this.placeholder = 'Email'" value="{{ old('email') }}" required>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>


                                {{-- Field Username (Untuk Login/Tampilan) --}}
                                <div class="col-md-12 form-group">
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" placeholder="Username" onfocus="this.placeholder = ''"
                                        onblur="this.placeholder = 'Username'" value="{{ old('name') }}" required>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>


                                {{-- Field Nomor Telepon --}}
                                <div class="col-md-12 form-group">
                                    <input type="text" class="form-control @error('phone_number') is-invalid @enderror"
                                        id="phone_number" name="phone_number" placeholder="Phone Number"
                                        onfocus="this.placeholder = ''" onblur="this.placeholder = 'Phone Number'"
                                        value="{{ old('phone_number') }}">
                                    @error('phone_number')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>


                                {{-- Field Nama Perusahaan --}}
                                <div class="col-md-12 form-group">
                                    <input type="text" class="form-control @error('company_name') is-invalid @enderror"
                                        id="company_name" name="company_name" placeholder="Company Name"
                                        onfocus="this.placeholder = ''" onblur="this.placeholder = 'Company Name'"
                                        value="{{ old('company_name') }}">
                                    @error('company_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>


                                {{-- Field Password --}}
                                <div class="col-md-12 form-group">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password" placeholder="Password"
                                        onfocus="this.placeholder = ''" onblur="this.placeholder = 'Password'" required>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>


                                {{-- Field Konfirmasi Password --}}
                                <div class="col-md-12 form-group">
                                    <input type="password" class="form-control" id="password-confirm"
                                        name="password_confirmation" placeholder="Confirm Password"
                                        onfocus="this.placeholder = ''" onblur="this.placeholder = 'Confirm Password'"
                                        required>
                                </div>


                                <div class="col-md-12 form-group">
                                    <button type="submit" value="submit" class="primary-btn">create</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endsection


    <!--================End Registration Box Area =================-->


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
