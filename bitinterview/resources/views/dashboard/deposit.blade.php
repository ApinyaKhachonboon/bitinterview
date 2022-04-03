@extends('../layouts/app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="card">
                <div class="card-header">
                    Deposit
                </div>
                <div class="card-body">
                    {!! Form::open(['url' => 'dashboard/deposit', 'method' => 'post']) !!}
                        {{ Form::token() }}
                        <div class="form-group">
                            {{ Form::label('fiat', 'Your Fiat Wallet') }}
                            {{ Form::select('currency_id', $list_national, $currency_id, ['class' => 'form-control', 'id'=>'fiat']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('amount', 'Amount') }}
                            <div class="form-inline">
                                {{ Form::number('amount', 0, ['oninput'=>"this.value = Math.abs(this.value)", 'class' => 'form-control col-10']) }}
                                {{ Form::text('fiat_name', $fiat, ['readonly', 'class' => 'form-control text-center col-2']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('balance', 'Your balance') }}
                            <div class="form-inline">
                                {{ Form::text('balance', $balance, ['readonly', 'class' => 'form-control col-10']) }}
                                {{ Form::text('fiat_name', $fiat, ['readonly', 'class' => 'form-control text-center col-2']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::submit('Deposit', ['class' => 'btn btn-info mt-5 w-100']) }}
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
            window.location = $("#fiat option:selected").text();
        });
    </script>
@endsection