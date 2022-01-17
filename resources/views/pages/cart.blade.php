@extends('layouts.app')

@section('content')
<div class="cart-section">
    <div class="container">
        <h1>{{__('cart.title')}}</h1>
        <div class="cart-container mt-5">
            @if (count($cart_items) > 0)
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col" class="display-none"></th>
                            <th scope="col">{{__('cart.product_column')}}</th>
                            <th scope="col" class="text-center">{{__('cart.quantity_column')}}</th>
                            <th scope="col">{{__('cart.price_column')}}</th>
                            <th></th>
                            <th class="display-none"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cart_items as $item)
                            <tr>
                                <td class="display-none">
                                    <img src="{{env('APP_URL')}}/storage/{{$item->product->image}}" height="70px" width="70px" alt="product picture">
                                </td>
                                <td>
                                    <h5>{{$item->product->title}}</h5>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <button class="btn btn-secondary display-none"  onclick="decrementQuantity({{$item->id}})">-</button>
                                        </div>
                                        <input type="text" class="form-control text-center" id="product-quantity-{{$item->id}}" onchange="setQuantity({{$item->id}})" value="{{$item->quantity}}" max="{{$item->product->quantity}}}">
                                        <div class="input-group-append">
                                            <button class="btn btn-secondary display-none" onclick="incrementQuantity({{$item->id}})">+</button>
                                        </div>
                                    </div>
                                </td>
                                <td>{{$item->product->price}} DH</td>
                                <td class="text-right">
                                    <form action="/command/{{$item->id}}" method="post">
                                        {{ csrf_field() }}
                                        <button class="btn btn-success" type="submit">{{__('cart.buy')}}</button>
                                    </form>
                                </td>
                                <td class="text-right display-none">
                                    <form action="/cart/{{$item->id}}" method="post">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                        <button class="btn btn-danger" type="submit"><i class="fa fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                      <li class="page-item">
                            <a class="page-link" href="{{$cart_items->previousPageUrl()}}" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                                <span class="sr-only">Previous</span>
                            </a>
                      </li>
                      @for ($i = 1; $i <= $cart_items->lastPage(); $i++)
                        <li class="page-item"><a class="page-link" href="/cart?page={{$i}}">{{$i}}</a></li>
                      @endfor
                      <li class="page-item">
                            <a class="page-link" href="{{$cart_items->nextPageUrl()}}" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                                <span class="sr-only">Next</span>
                            </a>
                      </li>
                    </ul>
                </nav>
                <div class="cart-footer mt-3 row">
                    <div class="col-6 mt-3">
                        {{__('cart.total')}} : <span id="total-price">{{$total}} DH<span>
                    </div>
                    <div class="col-6 text-right">
                        <div class="btn-group">
                            <a class="btn btn-success" href="/command/all" onclick="event.preventDefault();document.getElementById('buy-all-form').submit();">{{__('cart.buy_all')}}</a>
                            <a class="btn btn-danger" href="/cart" onclick="event.preventDefault();document.getElementById('delete-all-form').submit();">{{__('cart.delete_all')}}</a>
                        </div>
                        <form class="mt-2" id="buy-all-form" action="/command/all" class="d-none" method="post">
                            {{ csrf_field() }}
                        </form>
                        <form class="mt-2" id="delete-all-form" action="/cart" class="d-none" method="post">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                        </form>
                    </div>
                </div>
            @else
                <p class="text-center">{{__('cart.no_product')}}</p>
            @endif
        </div>
    </div>
</div>

<script>
    function setQuantity(id){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'PUT',
            url: '/cart/'+id,
            data: {
                quantity : $(`#product-quantity-${id}`).val()
            },
            success: function (data) {
                $(`#product-quantity-${id}`).val(data.msg);
                $("#total-price").html(data.total+" DH");
            },
            error: function (data) {
                console.log("data");
            }
        });
    }
    function decrementQuantity(id){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'PUT',
            url: '/cart/'+id,
            data: {
                decrement : true
            },
            success: function (data) {
                $(`#product-quantity-${id}`).val(data.msg);
                $("#total-price").html(data.total+" DH");
            },
            error: function (data) {
                console.log(data);
            }
        });
    }
    function incrementQuantity(id){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'PUT',
            url: '/cart/'+id,
            data: {
                increment : true
            },
            success: function (data) {
                $(`#product-quantity-${id}`).val(data.msg);
                $("#total-price").html(data.total+" DH");
            },
            error: function (data) {
                console.log(data);
            }
        });
    }
</script>
@endsection
