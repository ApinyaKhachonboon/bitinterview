@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    <div class="row justify-content-center flex-column p-5">
                        <button onclick="window.location.href='{{ URL::to('dashboard/deposit/THB') }}'" class="btn btn-info">Deposit</button>
                        <button onclick="window.location.href='{{ URL::to('dashboard/edituser') }}'" class="btn mt-3">Edit Profile</button>
                        {{-- <button onclick="" class="btn mt-3">Your Trade</button> --}}
                        <button onclick="window.location.href='{{ URL::to('dashboard/createbuy') }}'" class="btn btn-success mt-3">Open Buy</button>
                        <button onclick="window.location.href='{{ URL::to('dashboard/createsell') }}'" class="btn btn-danger mt-3">Open Sell</button>
                        <button onclick="window.location.href='{{ URL::to('dashboard/transfer') }}'" class="btn btn-warning mt-3">Transfer</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
