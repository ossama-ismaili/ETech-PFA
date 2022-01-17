@extends('layouts.app')

@section('content')
<div class="home-section">
    <div class="container">
        <h1 class="text-center rcmd-title">{{__('home.recommendations')}}</h1>
        <div class="row">
            @if (count($products) > 0)
                @foreach ($products as $item)
                    <div class="col-md-6 col-lg-4">
                        <div class="product-card my-2 card">
                            <div class="product-card-container">
                                <a href="/products/{{$item->id}}">
                                    <img src="{{env('APP_URL')}}/storage/{{$item->image}}" alt="product img" class="card-img-top product-card-img" width="100%" height="250px">
                                    <div class="product-card-middle">
                                        <div class="product-card-text">
                                            <i class="fa fa-shopping-bag"></i>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="card-body">
                                @if (isset($item->promotion))
                                    <span class="badge badge-danger float-right">-{{$item->promotion->promo}}%</span>
                                @endif
                                <div class="product-title card-title">
                                    <h1>{{$item->title}}</h1>
                                </div>
                                @if (count($item->reviews)>0)
                                    @for ($i = 0; $i < intval($item->reviews->avg('rating')); $i++)
                                        <span class="fa fa-star text-warning"></span>
                                    @endfor
                                    @for ($i = intval($item->reviews->avg('rating')); $i < 5; $i++)
                                        <span class="fa fa-star text-secondary"></span>
                                    @endfor
                                    ({{count($item->reviews)}})
                                @else
                                    <p class="text-muted">No reviews</p>
                                @endif
                                <p class="product-text card-text">Price : {{$item->price}} DH</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <p class="mx-auto">No product</p>
            @endif
            <div class="col-md-4 mx-auto mt-3">
                <a class="btn btn-info text-white btn-block" href="/products/all">{{__('home.view_products')}}</a>
            </div>
        </div>

        <h1 class="text-center rcmd-title mt-5">{{__('home.promotions')}}</h1>
        <div class="row">
            @if (count($promotions) > 0)
                @foreach ($promotions as $item)
                    <div class="col-md-6 col-lg-4">
                        <div class="product-card my-2 card">
                            <div class="product-card-container">
                                <a href="/products/{{$item->product->id}}">
                                    <img src="{{env('APP_URL')}}/storage/{{$item->product->image}}" alt="product img" class="card-img-top product-card-img" width="100%" height="250px">
                                    <div class="product-card-middle">
                                        <div class="product-card-text">
                                            <i class="fa fa-shopping-bag"></i>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="card-body">
                                <span class="badge badge-danger float-right">-{{$item->promo}}%</span>
                                <div class="product-title card-title">
                                    <h1>{{$item->product->title}}</h1>
                                </div>
                                @if (count($item->product->reviews)>0)
                                    @for ($i = 0; $i < intval($item->product->reviews->avg('rating')); $i++)
                                        <span class="fa fa-star text-warning"></span>
                                    @endfor
                                    @for ($i = intval($item->product->reviews->avg('rating')); $i < 5; $i++)
                                        <span class="fa fa-star text-secondary"></span>
                                    @endfor
                                    ({{count($item->product->reviews)}})
                                @else
                                    <p class="text-muted">No reviews</p>
                                @endif
                                <p class="product-text card-text">Price : {{$item->product->price}} DH</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <p class="mx-auto">No Promotions</p>
            @endif
        </div>
    </div>
</div>
@endsection
