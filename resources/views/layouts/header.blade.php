@php
    use App\Models\Cart;
    $cart;
    if(Auth::check()){
        $cart=Cart::where('user_id',Auth::user()->id)->first();
    }
@endphp

<header class="header-container">
    <nav id="navbar" class="navbar navbar-expand-md navbar-dark navbar-transparent fixed-top scrolling-navbar">
        <div class="container-fluid">
            <a class="navbar-brand" href="/"><img src="{{asset('images/icon.png')}}" width="25px" alt="iconimg"> <strong>ETECH</strong></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item ml-4">
                        <a class="nav-link link-effect" href="/about"><span class="noselect">{{__('header.about_us')}}</span></a>
                    </li>
                    <li class="nav-item ml-4">
                        <a class="nav-link link-effect" href="/contact"><span class="noselect">{{__('header.contact_us')}}</span></a>
                    </li>
                    @guest
                        <li class="nav-item ml-4">
                            <a class="nav-link link-effect" href="{{ route('login') }}"><span class="noselect"><span class="fa fa-sign-in"></span> {{__('header.login')}}</span></a>
                        </li>
                    @else
                        <li class="nav-item ml-4">
                            <a class="nav-link" href="/cart">
                                <span class="icon-container">
                                    <i class="fa fa-shopping-cart"></i>
                                </span>
                                <span class="badge badge-danger">
                                    {{$cart->cart_items()->count()}}
                                </span>
                            </a>
                        </li>
                        <li class="nav-item ml-4">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                <img class="rounded" src="{{env('APP_URL')}}/storage/{{Auth::user()->avatar}}" width="25px" />
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <div class="dropdown-item" href="/profile">
                                    <a class="btn btn-info btn-block" href="/profile">
                                        <span class="fa fa-user"></span>
                                        {{__('header.profile')}}
                                    </a>
                                </div>
                                <div class="dropdown-item">
                                    <a class="btn btn-warning btn-block text-white" href="/payment/history">
                                        <span class="fa fa-history"></span>
                                        {{__('header.payment_history')}}
                                    </a>
                                </div>
                                <div class="dropdown-item">
                                    <a class="btn btn-danger btn-block" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                        <span class="fa fa-sign-out"></span>
                                        {{__('header.logout')}}
                                    </a>
                                </div>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
    <div class="jumbotron jumbotron-image color-grey-light">
        <div class="mask-black d-flex align-items-center">
            <div class="container text-center text-white py-5">
                <h1 class="mb-3">ETech</h1>
                <p class="mb-4">{{__('header.subtitle')}}</p>
                <div class="input-group col-sm-10 col-md-8 col-lg-6 ml-lg-auto mx-sm-auto mb-3">
                    <input type="text" class="form-control" placeholder="{{__('header.search')}}" aria-label="search" aria-describedby="search input" id="searchInput" onkeypress="if(event.key === 'Enter')document.getElementById('searchBtn').click();" required>
                    <div class="input-group-append">
                        <button class="btn btn-outline-info" type="button" onclick="if(document.getElementById('searchInput').value.length>0) window.location.replace('/products/search/'+document.getElementById('searchInput').value);" id="searchBtn" ><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </div>
          </div>
    </div>
</header>

<script>
    window.onscroll=()=>{
        const scrollPos=window.pageYOffset;
        var nav=document.getElementById("navbar");
        if(scrollPos > 0){
            nav.classList.add("navbar-blacked");
            nav.classList.remove("navbar-transparent");
        }
        else{
            nav.classList.add("navbar-transparent");
            nav.classList.remove("navbar-blacked");
        }
    }
</script>
