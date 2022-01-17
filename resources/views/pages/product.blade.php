
@extends('layouts.app')

@section('content')

@if (Auth::check())
    @if ($review_count > 0)
    <div class="modal fade" id="editReviewFormModal" tabindex="-1" role="dialog" aria-labelledby="editReviewFormModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="editReviewForm" method="post">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}
                    <div class="modal-header">
                        <h5 class="modal-title" id="editReviewFormModalLabel">{{__('product.edit_review')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group col-12">
                                <div class="rating">
                                    <input type="radio" name="rating" value="5" id="5"><label for="5">☆</label>
                                    <input type="radio" name="rating" value="4" id="4"><label for="4">☆</label>
                                    <input type="radio" name="rating" value="3" id="3"><label for="3">☆</label>
                                    <input type="radio" name="rating" value="2" id="2"><label for="2">☆</label>
                                    <input type="radio" name="rating" value="1" id="1"><label for="1">☆</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-12">
                                <textarea class="form-control" id="editReviewFormComment" name="comment" cols="30" rows="5" placeholder="{{__('product.review_body')}}"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('product.cancel')}}</button>
                        <button class="btn btn-primary" type="submit">{{__('product.save')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
@endif

<div class="product-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2>
                    <span class="title">{{$product->title}}</span>
                    @if(isset($product->promotion))
                        <span class="badge badge-danger">-{{$product->promotion->promo}}%</span>
                    @endif
                    @if($product->quantity==0)
                        <span class="badge badge-secondary float-md-right">{{__('product.not_available')}}</span>
                    @endif
                </h2>
            </div>
        </div>
        <div class="row">
            <img src="{{env('APP_URL')}}/storage/{{$product->image}}" alt="product img" class="col-md-4" />
            <div class="col-md-8">
                <div class="row">
                    <div class="col-12 mt-3">
                        {{__('product.price')}} <span class="float-right">{{$product->price}} DH</span>
                    </div>
                    <div class="col-12 mt-3">
                        {{__('product.category')}}
                        <span class="float-right">
                            {{$product->subcategory->category->name}}
                            <span class="fa fa-arrow-right"></span>
                            <a href="/products/category/{{$product->subcategory->id}}"> {{$product->subcategory->name}}</a>
                        </span>
                    </div>
                    <div class="col-12 mt-3">
                        {{__('product.rating')}}
                        <span class="float-right">
                            @if (count($product->reviews)>0)
                                @for ($i = 0; $i < intval($product->reviews->avg('rating')); $i++)
                                    <span class="fa fa-star text-warning"></span>
                                @endfor
                                @for ($i = intval($product->reviews->avg('rating')); $i < 5; $i++)
                                    <span class="fa fa-star text-secondary"></span>
                                @endfor
                                ({{count($product->reviews)}})
                            @else
                                <p class="text-muted">{{__('product.no_reviews')}}</p>
                            @endif
                        </span>
                    </div>

                    <div class="col-12 mt-3">
                        {{__('product.quantity')}} <span class="float-right"><input type="number" class="text-center" value="1" id="product-quantity" min="1" max="{{$product->quantity}}"></span>
                    </div>
                    @if($product->quantity>0)
                        <div class="col-12 mt-3 p-0 text-right">
                            <div class="btn-group col-lg-6" role="group" aria-label="Options">
                                @guest
                                    <a class="btn btn-success text-left" href="/login"><span class="fa fa-money mr-2"></span> {{__('product.buy_now')}}</a>
                                    <a class="btn btn-primary text-left" href="/login"><span class="fa fa-shopping-cart mr-2"></span> {{__('product.add_to_cart')}}</a>
                                @else
                                    <button class="btn btn-success text-left" onclick="buyNow({{$product->id}})"><span class="fa fa-money mr-2"></span> {{__('product.buy_now')}}</button>
                                    <button class="btn btn-primary text-left" onclick="addToCart({{$product->id}})"><span class="fa fa-shopping-cart mr-2"></span> {{__('product.add_to_cart')}}</button>
                                @endguest
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-12 mt-4">
                <h3>{{__('product.description')}}</h3>
                <p>{!! $product->description !!}</p>
            </div>
        </div>
        @if (Auth::check())
            @if ($command_count > 0 && $review_count == 0)
            <div class="row">
                <div class="col-lg-5 col-md-6 col-sm-12 mx-auto mb-4">
                    <div id="rating-form">
                        <button class="btn btn-secondary float-right" onclick="$('#rating-form').addClass('hide');"><i class="fa fa-close"></i></button>
                        <form action="/review/{{$product->id}}" method="post">
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <div class="rating">
                                        <input type="radio" name="rating" value="5" id="5" /><label for="5">☆</label>
                                        <input type="radio" name="rating" value="4" id="4" /><label for="4">☆</label>
                                        <input type="radio" name="rating" value="3" id="3" /><label for="3">☆</label>
                                        <input type="radio" name="rating" value="2" id="2" /><label for="2">☆</label>
                                        <input type="radio" name="rating" value="1" id="1" /><label for="1">☆</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <textarea class="form-control" name="comment" cols="30" rows="5" placeholder="{{__('product.review_body')}}"></textarea>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <button class="btn btn-primary" type="submit">{{__('product.submit')}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif
        @endif
        <div class="row">
            <div class="col-12"><h3>{{__('product.reviews')}}</h3></div>
            @if (count($product->reviews))
                @foreach ($product->reviews as $item)
                    <div class="col-12 mb-3">
                        <div class="media review-container">
                            <img class="mr-3 rounded" width="50px" src="{{env('APP_URL')}}/storage/{{$item->user->avatar}}" alt="Generic placeholder image">
                            <div class="media-body">
                                @if (Auth::check())
                                    @if (Auth::user()->id==$item->user_id)
                                    <div class="float-right">
                                        <button class="btn btn-link text-info" onclick="updateEditForm({{$item->id}},'{{$item->comment}}')" data-toggle="modal" data-target="#editReviewFormModal">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                        <form class="d-inline" action="/review/{{$item->id}}" method="post" onsubmit="return confirm('{{__('product.confirm')}}');">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                            <button type="submit" class="btn btn-link text-danger">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                    @endif
                                @endif
                                <h5 class="mt-0">{{$item->user->name}}</h5>
                                <p style="margin: 0;">
                                    @for ($i = 0; $i < $item->rating ; $i++)
                                        <span class="fa fa-star text-warning"></span>
                                    @endfor
                                    @for ($i = $item->rating; $i < 5; $i++)
                                        <span class="fa fa-star text-secondary"></span>
                                    @endfor
                                </p>
                                <p>{{$item->comment}}</p>
                                <p class="text-muted">{{__('product.reviewed_on')}} {{date_format(new DateTime($item->created_at), 'F d, Y')}}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
            <div class="col-12">
                <p class="text-center text-muted">{{__('product.no_reviews')}}</p>
            </div>
            @endif
        </div>
    </div>
</div>
<script>
    function updateEditForm(id, comment) {
        $('#editReviewForm').attr('action','/review/'+id);
        $('#editReviewFormComment').val(comment);
    }

    function buyNow(id){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'POST',
            url: `/command/buynow/${id}`,
            data: {
                quantity : $('#product-quantity').val()
            },
            success:function(){
                window.location.replace("/payment");
            }
        });
    }
    function addToCart(id) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'POST',
            url: `/cart/${id}`,
            data: {
                quantity : $('#product-quantity').val()
            },
            success:function(){
                window.location.replace("/cart");
            }
        });
    }
</script>
@endsection
