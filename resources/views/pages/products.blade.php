@extends('layouts.app')

@section('content')
<div class="products-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-lg-4 col-xl-3">
                <ul class="list-unstyled">
                    <h4>Categories</h4>
                    <li>
                        <a class="btn btn-outline-info col-12 mt-2" href="/products/all">ALL</a>
                    </li>
                    @foreach ($categories as $item)
                        <li class="row">
                            <div class="btn-group col-12 dropright mt-2">
                                <button type="button" class="btn btn-outline-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{$item->name}}
                                </button>
                                <div class="dropdown-menu">
                                    @foreach ($item->subcategories as $sub)
                                        <a class="dropdown-item" href="/products/category/{{$sub->id}}">{{$sub->name}}</a>
                                    @endforeach
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="products-container col-md-12 col-lg-8 col-xl-9 row">
                @if (count($products) > 0)
                    @foreach ($products as $item)
                        <div class="col-md-6 col-lg-4">
                            <div class="product-card my-2 card">
                                <div class="product-card-container">
                                    <a href="/products/{{$item->id}}">
                                        <img src="{{env('APP_URL')}}/storage/{{$item->image}}" alt="product img" class="card-img-top product-card-img" width="100%" height="250px">
                                        @if ($item->quantity>0)
                                            <div class="product-card-middle">
                                                <div class="product-card-text">
                                                    <i class="fa fa-shopping-bag"></i>
                                                </div>
                                            </div>
                                        @else
                                            <div class="product-text-middle">
                                                <span class="badge badge-secondary">NON DISPONIBLE</span>
                                            </div>
                                        @endif
                                    </a>
                                </div>
                                <div class="card-body">
                                    @if (isset($item->promotion))
                                        <span class="badge badge-danger float-right">-{{$item->promotion->promo}}%</span>
                                    @endif
                                    <div class="product-title card-title">
                                        <h1>
                                            {{$item->title}}
                                        </h1>
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
            </div>
            <div class="offset-lg-4 offset-xl-3 col-md-12">
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                      <li class="page-item">
                            <a class="page-link" href="{{$products->previousPageUrl()}}" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                                <span class="sr-only">Previous</span>
                            </a>
                      </li>
                      @for ($i = 1; $i <= $products->lastPage(); $i++)
                        <li class="page-item"><a class="page-link" href="/products/all?page={{$i}}">{{$i}}</a></li>
                      @endfor
                      <li class="page-item">
                            <a class="page-link" href="{{$products->nextPageUrl()}}" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                                <span class="sr-only">Next</span>
                            </a>
                      </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
@endsection
