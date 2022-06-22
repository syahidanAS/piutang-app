@extends('main')

@section('title', 'Dashboard')

@section('breadcrumbs')
<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>Dashboard</h1>
            </div>
        </div>
    </div>
</div>

@endsection

@section('content')
<div class="sufee-login d-flex align-content-center flex-wrap">
    <div class="container text-center" style="margin-top: 7%; margin-bottom:2%;">
        <img src="{{ asset('images/hospital-logo.png') }}" alt="" style="width: 100px;">
    </div>
</div>
<div class="sufee-login d-flex align-content-center flex-wrap">
    <div class="container mt-4" style="margin-left: 19%;">
        <div class="col-md-3">
            <div class="card" style="border-radius: 0px 30px 0px 0px;  border: 1px solid #000000;">
                <div class="card-body">
                    <strong class="card-title">@currency($totalPiutang)</strong>
                </div>
                <div class="card-footer text-center" >
                    <strong>Total Piutang</strong>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card" style="border-radius: 0px 30px 0px 0px; border: 1px solid #000000;">
                <div class="card-body">
                    <strong class="card-title">@currency($totalPembayaran)</strong>
                </div>
                <div class="card-footer text-center">
                    <strong>Realisasi Piutang</strong>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card" style="border-radius: 0px 30px 0px 0px; border: 1px solid #000000;">
                <div class="card-body">
                    <strong class="card-title">@currency($sisaPiutang)</strong>
                </div>
                <div class="card-footer text-center">
                    <strong>Sisa Piutang</strong>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
