{{-- resources/views/welcome.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Dashboard Utama</h1>
    
    <!-- Top 5 Barang Paling Laku -->
    <div class="card mb-4">
        <div class="card-header">
            <h3>Top 5 Barang Paling Laku</h3>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach($topProducts as $product)
                <div class="col-md-2">
                    <div class="card">
                        <div class="card-body">
                            <h5>{{ $product->name }}</h5>
                            <p>Terjual: {{ $product->total_sold ?? 0 }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Top 1 Kategori -->
    <div class="card mb-4">
        <div class="card-header">
            <h3>Kategori Paling Laku</h3>
        </div>
        <div class="card-body">
            @if($topCategory)
            <h4>{{ $topCategory->name }}</h4>
            <p>Total Terjual: {{ $topCategory->total_sold ?? 0 }}</p>
            @endif
        </div>
    </div>

    <!-- Top 3 Spender -->
    <div class="card mb-4">
        <div class="card-header">
            <h3>Top 3 Spender</h3>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach($topSpenders as $spender)
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5>{{ $spender->name }}</h5>
                            <p>Total Belanja: Rp {{ number_format($spender->total_spent, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Top Buyer -->
    <div class="card mb-4">
        <div class="card-header">
            <h3>Top Buyer</h3>
        </div>
        <div class="card-body">
            @if($topBuyer)
            <h4>{{ $topBuyer->name }}</h4>
            <p>Total Item: {{ $topBuyer->total_items ?? 0 }}</p>
            @endif
        </div>
    </div>
</div>
@endsection