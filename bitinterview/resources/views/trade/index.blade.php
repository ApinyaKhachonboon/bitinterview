@extends('../layouts/master')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="card">
                <div class="card-header">
                    from {{ $market_post->users->name . ' ' . $market_post->action }}
                </div>
                <div class="card-body">
                    {!! Form::open(['url' => 'trade/'. $id, 'method' => 'post']) !!}
                        {{ Form::token() }}
                        <div class="form-group">
                            {{ Form::label('price', 'Price') }}
                            <div class="form-inline">
                                {{ Form::text('price', number_format($market_post->price, 2), ['readonly', 'class' => 'form-control col-10', 'id' => 'price']) }}
                                {{ Form::text('fiat_name', $fiat_name, ['readonly', 'class' => 'form-control text-center col-2']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('available', 'Available') }}
                            <div class="form-inline">
                                {{ Form::text('available', number_format($market_post->amount, 8), ['readonly', 'class' => 'form-control col-10', 'id' => 'available']) }}
                                {{ Form::text('crypto_name', $market_post->currencies->name, ['readonly', 'class' => 'form-control text-center col-2']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('limit', 'Limit') }}
                            <div class="form-inline">
                                {{ Form::text('limit', 
                                number_format($market_post->min, 2) . " - " . number_format($market_post->max, 2), 
                                ['readonly', 'class' => 'form-control col-10', 'id' => 'available']) 
                                }}
                                {{ Form::text('fiat_name', $fiat_name, ['readonly', 'class' => 'form-control text-center col-2']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('balance', 'Your Balance') }}
                            <div class="form-inline">
                                @if ($market_post->action == 'buy')
                                    {{ Form::text('balance', number_format($balance, 8), ['readonly', 'class' => 'form-control col-10', 'id' => 'balance']) }}
                                    {{ Form::text('fiat_name', $crypto_name, ['readonly', 'class' => 'form-control text-center col-2']) }}
                                @elseif($market_post->action == 'sell')
                                    {{ Form::text('balance', number_format($balance, 2), ['readonly', 'class' => 'form-control col-10', 'id' => 'balance']) }}
                                    {{ Form::text('fiat_name', $fiat_name, ['readonly', 'class' => 'form-control text-center col-2']) }}
                                @endif
                                
                                
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('amount', 'Amount') }}
                            <div class="form-inline">
                                {{ Form::number('amount', 0, [ 'step' => 1e-8, 'min' => 0, 'max' => min($balance, $market_post->amount),'class' => 'form-control col-10', 'id' => 'amount']) }}
                                {{ Form::text('crypto_name', 'BTC', ['readonly', 'class' => 'form-control text-center col-2']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            @if ($market_post->action == 'buy')
                                {{ Form::submit('Sell', ['class' => 'btn btn-danger mt-5 w-100']) }}
                            @elseif($market_post->action == 'sell')
                                {{ Form::submit('Buy', ['class' => 'btn btn-success mt-5 w-100']) }}
                            @endif
                            
                            <a href="{{ URL::to('/') }}" class="btn btn-secondary mt-3 w-100">Cancel</a>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('#amount').change(function(){
            $(this).val(parseFloat($(this).val()).toFixed(8));
        });
    </script>
@endsection