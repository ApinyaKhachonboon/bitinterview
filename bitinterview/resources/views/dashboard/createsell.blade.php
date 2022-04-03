@extends('../layouts/app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="card">
                <div class="card-header">
                    Create Sell
                </div>
                <div class="card-body">
                    {!! Form::open(['url' => 'dashboard/createsell', 'method' => 'post']) !!}
                        {{ Form::token() }}
                        <div class="form-group">
                            {{ Form::label('currencies', 'Crypto') }}
                            {{ Form::select('currency_id', $list_crypto, $crypto_id, ['class' => 'form-control', 'id'=>'crypto']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('fiat', 'Select Fiat') }}
                            {{ Form::select('fiat_id', $list_national, $fiat_id, ['class' => 'form-control', 'id'=>'fiat']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('balance', 'Your balance') }}
                            <div class="form-inline">
                                {{ Form::text('balance', $balance, ['readonly', 'class' => 'form-control col-10', 'id' => 'balance']) }}
                                {{ Form::text('crypto_name', $crypto_name, ['readonly', 'class' => 'form-control text-center col-2']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('amount', 'Amount') }}
                            <div class="form-inline">
                                {{ Form::number('amount', $amount_max, [ 'step' => 1e-8, 'min' => 0, 'max' => $amount_max,'class' => 'form-control col-10', 'id' => 'amount']) }}
                                {{ Form::text('crypto_name', $crypto_name, ['readonly', 'class' => 'form-control text-center col-2']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('price', 'Price') }}
                            <div class="form-inline">
                                {{ Form::number('price', $price, [ 'step' => 0.001, 'min' => 0,'class' => 'form-control col-10', 'id' => 'price']) }}
                                {{ Form::text('fiat_name', $fiat_name, ['readonly', 'class' => 'form-control text-center col-2']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('min', 'limit-min') }}
                            <div class="form-inline">
                                @if ($fiat_name == 'THB')
                                    @php $min = 50; @endphp
                                @else
                                    @php $min = 2; @endphp
                                @endif
                                {{ Form::number('min', $min, ['min'=> $min, 'oninput'=>"this.value = Math.abs(this.value)", 'class' => 'form-control col-10']) }}
                                {{ Form::text('fiat_name', $fiat_name, ['readonly', 'class' => 'form-control text-center col-2']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('max', 'limit-max') }}
                            <div class="form-inline">
                                {{ Form::number('max', $limit_max, ['min'=> '0', 'max'=> $limit_max, 'oninput'=>"this.value = Math.abs(this.value)", 'class' => 'form-control col-10', 'id'=>'max']) }}
                                {{ Form::text('fiat_name', $fiat_name, ['readonly', 'class' => 'form-control text-center col-2']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::hidden('hidden-balance', $balance, ['id' => 'hidden-balance']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::submit('Open Sell', ['class' => 'btn btn-danger mt-5 w-100']) }}
                            <a href="{{ URL::to('/dashboard') }}" class="btn btn-secondary mt-3 w-100">Cancel</a>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('#fiat').change(function(){
            window.location.href  = "/dashboard/createsell/" +  
            $("#fiat option:selected").text() + "/" + 
            $("#crypto option:selected").text();
        });

        $('#crypto').change(function(){
            window.location.href  = "/dashboard/createsell/" +  
            $("#fiat option:selected").text() + "/" + 
            $("#crypto option:selected").text();
        });

        $('#amount').change(function(){
            $('#price').val(parseFloat($('#price').val()).toFixed(2));
            var new_amount = 0;
            new_amount = parseFloat($('#amount').val()) * parseFloat($('#price').val());
            $('#max').attr('max', parseInt(new_amount));
            $('#max').val(parseInt(new_amount));  
        });

        $('#price').change(function(){
            var new_amount = 0;
            new_amount = parseFloat($('#amount').val()) * parseFloat($(this).val());
            $('#max').attr('max', parseInt(new_amount));
            $('#max').val(parseInt(new_amount));       
        });
    </script>
@endsection