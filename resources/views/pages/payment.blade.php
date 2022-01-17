@extends('layouts.app')

@section('content')
<section class="payment-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <li class="page-item">
                            <a class="page-link" href="{{$commands->previousPageUrl()}}" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                                <span class="sr-only">Previous</span>
                            </a>
                        </li>
                        @for ($i = 1; $i <= $commands->lastPage(); $i++)
                            <li class="page-item"><a class="page-link" href="/payment?page={{$i}}">{{$i}}</a></li>
                        @endfor
                        <li class="page-item">
                            <a class="page-link" href="{{$commands->nextPageUrl()}}" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                                <span class="sr-only">Next</span>
                            </a>
                        </li>
                    </ul>
                </nav>
                @foreach ($commands as $item)
                <div class="media command-item mb-2 p-1">
                    <img class="mr-3" src="{{env('APP_URL')}}/storage/{{$item->product->image}}" width="50px" height="50px" alt="Generic placeholder image">
                    <div class="media-body">
                        <div class="float-right">
                            <form class="d-inline" action="/command/{{$item->id}}" method="post">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <button type="submit" class="btn btn-link text-danger">
                                    <i class="fa fa-close"></i>
                                </button>
                            </form>
                        </div>
                        <h5 class="mt-0">{{$item->product->title}}</h5>
                        <table>
                            <tr>
                                <td class="arg">Price</td>
                                <td>{{$item->product->price}}</td>
                            </tr>
                            <tr>
                                <td class="arg">Quantity</td>
                                <td>{{$item->quantity}}</td>
                            </tr>
                        </table>
                    </div>
                  </div>
                @endforeach
                <p>Total : {{$total}}</p>
            </div>
            <div class="col-lg-8 col-md-6">
                <div class="panel panel-default">
                    <div class="panel-body">
                        @if (Session::has('error'))
                            <div class="alert alert-danger text-center">
                                <p>{{ Session::get('error') }}</p>
                            </div>
                        @endif
                        @if (Session::has('success'))
                            <div class="alert alert-primary text-center">
                                <p>{{ Session::get('success') }}</p>
                            </div>
                        @endif
                        @if (count($commands)>0)
                            <form action="{{ route('make-payment') }}" method="POST">
                                @csrf
                                <script
                                        src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                        data-key="{{config('app.stripe_key')}}"
                                        data-amount="{{$total*100}}"
                                        data-name="Stripe Payment"
                                        data-description="Product Payment"
                                        data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
                                        data-locale="auto"
                                        data-currency="MAD">
                                </script>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
@endsection
