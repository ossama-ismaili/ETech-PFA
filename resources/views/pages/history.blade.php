@extends('layouts.app')

@section('content')
<div class="history-section">
    <div class="container">
        <h1 class="text-center history-title">{{__('history.title')}}</h1>
        <div class="history-container mt-5">
            @if (count($commands) > 0)
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col" class="display-none"></th>
                        <th scope="col">{{__('history.product_column')}}</th>
                        <th scope="col">{{__('history.quantity_column')}}</th>
                        <th scope="col">{{__('history.price_column')}}</th>
                        <th scope="col">{{__('history.status_column')}}</th>
                        <th scope="col" class="display-none">{{__('history.paid_date_column')}}</th>
                        <th class="display-none"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($commands as $item)
                    <tr>
                        <td class="display-none">
                            <a href="/products/{{$item->product->id}}">
                                <img src="{{env('APP_URL')}}/storage/{{$item->product->image}}" height="70px" width="70px" alt="product picture">
                            </a>
                        </td>
                        <td>
                            <h5>{{$item->product->title}}</h5>
                        </td>
                        <td>{{$item->quantity}}</td>
                        <td>{{$item->product->price}} DH</td>
                        <td>
                            @if ($item->status==0)
                                <a href="/payment">{{__('history.pending_status')}}</a>
                            @elseif($item->status==1)
                                {{__('history.paid_status')}}
                            @elseif($item->status==2)
                                {{__('history.complete_status')}}
                            @else
                                {{__('history.undefined_status')}}
                            @endif
                        </td>
                        <td class="display-none">
                            @if (isset($item->paid_at))
                                {{date_format(new DateTime($item->paid_at), 'F d, Y g:ia')}}
                            @else
                                {{__('history.not_paid')}}
                            @endif
                        </td>
                        <td class="text-right display-none">
                            <form action="/command/{{$item->id}}" method="post">
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
                        <a class="page-link" href="{{$commands->previousPageUrl()}}" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                            <span class="sr-only">Previous</span>
                        </a>
                  </li>
                  @for ($i = 1; $i <= $commands->lastPage(); $i++)
                    <li class="page-item"><a class="page-link" href="/payment/history?page={{$i}}">{{$i}}</a></li>
                  @endfor
                  <li class="page-item">
                        <a class="page-link" href="{{$commands->nextPageUrl()}}" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                            <span class="sr-only">Next</span>
                        </a>
                  </li>
                </ul>
            </nav>
            @else
                <p class="text-center">{{__('history.no_product')}}</p>
            @endif
        </div>
    </div>
</div>
@endsection
