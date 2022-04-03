@extends('../layouts/master')

@section('content')
    <nav class="navbar navbar-expand navbar-light bg-light">
        {{-- <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button> --}}
        <div class="collapse navbar-collapse container" id="navbarNavDropdown">
            <ul class="navbar-nav mr-5">
                @if ($action == "buy")
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <button type="button" onclick="window.location='{{ URL::to('/buy/' . $fiat . '/' . $crypto) }}'" class="btn btn-success" id="buy-btn">Buy</button>
                        <button type="button" onclick="window.location='{{ URL::to('/sell/' . $fiat . '/' . $crypto) }}'" class="btn" id="sell-btn">Sell</button>
                    </div>
                @else
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <button type="button" onclick="window.location='{{ URL::to('/buy/' . $fiat . '/' . $crypto) }}'" class="btn" id="buy-btn">Buy</button>
                        <button type="button" onclick="window.location='{{ URL::to('/sell/' . $fiat . '/' . $crypto) }}'" class="btn btn-danger" id="sell-btn">Sell</button>
                    </div>
                @endif

            </ul>
            <ul class="navbar-nav mr-auto">
                @if ($crypto == 'BTC')
                    <li class="nav-item active">
                        <a class="nav-link" href="{{ URL::to($action . '/' . $fiat . '/' . 'BTC') }}">BTC</a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ URL::to($action . '/' . $fiat . '/' . 'BTC') }}">BTC</a>
                    </li>
                @endif
                @if ($crypto == 'ETH')
                    <li class="nav-item active">
                        <a class="nav-link" href="{{ URL::to($action . '/' . $fiat . '/' . 'ETH') }}">ETH</a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ URL::to($action . '/' . $fiat . '/' . 'ETH') }}">ETH</a>
                    </li>
                @endif
                @if ($crypto == 'XRP')     
                    <li class="nav-item active">
                        <a class="nav-link" href="{{ URL::to($action . '/' . $fiat . '/' . 'XRP') }}">XRP</a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ URL::to($action . '/' . $fiat . '/' . 'XRP') }}">XRP</a>
                    </li>
                @endif

                @if ($crypto == 'DOGE')
                    <li class="nav-item active">
                        <a class="nav-link" href="{{ URL::to($action . '/' . $fiat . '/' . 'DOGE') }}">DOGE</a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ URL::to($action . '/' . $fiat . '/' . 'DOGE') }}">DOGE</a>
                    </li>
                @endif
            </ul>
        </div>
    </nav>
    <div class="container mt-5">
        <div class="row justify-content-end">
            <div class="form-check-inline mb-3">
                {{ Form::text('balance_cypto', number_format($balance_cypto, 8), ['readonly', 'class' => 'form-control text-center col-10']) }}
                {{ Form::text('fiat_name', $crypto, ['readonly', 'class' => 'form-control text-center col-2']) }}
            </div>
            <div class="form-check-inline mb-3">
                {{ Form::text('balance_fiat', number_format($balance_fiat, 2), ['readonly', 'class' => 'form-control text-center col-10']) }}
                {{ Form::text('fiat_name', $fiat, ['readonly', 'class' => 'form-control text-center col-2']) }}
            </div>
            <div class="form-check-inline mb-3">
                <label for="fiat" class="mr-2">Fiat</label>
                {{ Form::select('fiat_name', $list_national, $fiat_id, ['class' => 'form-control mr-2', 'id' => 'fiat']) }}
                <button class="btn" onclick="location.reload();">Refresh</button>
            </div>
        </div>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>Advertisers</th>
                    <th>Price</th>
                    <th>Limit/Available</th>
                    <th>Trade</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($market_posts as $value)
                    <tr>
                        <td>{{ $value->users->name }}</td>
                        <td>{{ number_format($value->price, 2) . " " . $fiat }}</td>
                        <td>
                            <div>
                                <div class="show-text">Available</div>
                                <div>
                                    {{ number_format($value->amount, 8) }}
                                    {{ $value->currencies->name }}
                                </div>
                            </div>
                            <div>
                                <div class="show-text">
                                    Limit
                                </div>
                                <div>
                                    @php
                                        $sign = "$";
                                        if($fiat == "THB")
                                        {
                                            $sign = "฿";
                                        }
                                    @endphp
                                    {{ $sign . number_format($value->min, 2) . " - " . $sign . number_format($value->max, 2) }}
                                </div>
                            </div>
                        </td>
                        @if ($action == "buy")
                            <td><a href="{{ URL::to('/trade/'.$value->id . '/' . $fiat . '/' . $crypto) }}" class="btn btn-success">{{ $action . " " . $value->currencies->name }}</a></td>
                        @else
                            <td><a href="{{ URL::to('/trade/'.$value->id . '/' . $fiat . '/' . $crypto) }}" class="btn btn-danger">{{ $action . " " . $value->currencies->name }}</a></td>
                        @endif
                        
                    </tr>
                @endforeach
                {{-- <tr>
                    <td>manude</td>
                    <td>1,500,000</td>
                    <td>
                        <div>
                            <div class="show-text">Available</div>
                            <div>
                                0.00582889 
                                BTC
                            </div>
                        </div>
                        <div>
                            <div class="show-text">
                                Limit
                            </div>
                            <div>
                                ฿500.00 - ฿4,000.00
                            </div>
                        </div>
                    </td>
                    <td><a href="#" class="btn btn-success">Buy BTC</a></td>
                </tr> --}}
            </tbody>
        </table>
        {{ $market_posts->links() }}
    </div>
@endsection

@section('script')
    <script>
        $('#fiat').change(function(){

            var crypto_name = '';
            $(".nav-item").each(function(){
                if($(this).hasClass('active'))
                {
                    crypto_name = $.trim($(this).text());
                }
            });
            window.location.href  = "/"+ '{{ $action }}'  +"/" +  
            $("#fiat option:selected").text() + "/" + 
            crypto_name;
        });
    </script>
@endsection