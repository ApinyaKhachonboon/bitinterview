@extends('../layouts/master')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="card">
                <div class="card-header">
                    Transfer
                </div>
                <div class="card-body">
                    {!! Form::open(['url' => 'dashboard/transfer', 'method' => 'post']) !!}
                        {{ Form::token() }}
                        <div class="form-group">
                            {{ Form::label('crypto', 'Select Currency') }}
                            <div class="form-inline">
                                <div class="form-group">
                                    {{ Form::select('currency_id', $crypto_list, $currency_id, ['class' => 'form-control', 'id'=>'crypto']) }}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('balance', 'Your balance') }}
                            <div class="form-inline">
                                {{ Form::text('balance', number_format($balance, 8), ['readonly', 'class' => 'form-control col-10', 'id' => 'balance']) }}
                                {{ Form::text('crypto_name', $crypto_name, ['readonly', 'class' => 'form-control text-center col-2']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('amount', 'Amount') }}
                            <div class="form-inline">
                                {{ Form::number('amount', 0, [ 'step' => 1e-8, 'min' => 0, 'max' => $balance,'class' => 'form-control col-10', 'id' => 'amount']) }}
                                {{ Form::text('crypto_name', $crypto_name, ['readonly', 'class' => 'form-control text-center col-2']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('to_user', 'To') }}
                            <div class="form-inline">
                                <div class="form-group">
                                    {{ Form::select('to', ['Bitinterview'=>'Bitinterview','Other' => 'Other'], 'Bitinterview', ['class' => 'form-control', 'id'=>'to']) }}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('emailOrOther', 'Fill Email or Other') }}
                            {{ Form::text('emailOrOther', null, ['class' => 'form-control', 'id' => 'emailOrOther']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::submit('Transfer', ['class' => 'btn btn-info mt-5 w-100']) }}
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
        $('#amount').change(function(){
            $(this).val(parseFloat($(this).val()).toFixed(8));
        });

        $('#crypto').change(function(){
            window.location.href  = "/dashboard/transfer/" + 
            $("#crypto option:selected").text();
        });
    </script>
@endsection