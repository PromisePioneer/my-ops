@extends('layouts.template')
@section('page-title', 'Detail PO ' . $goods->name)
@section('content')
    @if($goods->need_sn === 1)
        @include('pages.inventory.goods.stocks.detail.stock-with-sn')
    @else
        @include('pages.inventory.goods.stocks.detail.stock-no-sn')
    @endif

@endsection
