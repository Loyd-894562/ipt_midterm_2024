@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Order</h1>

    <!-- Display validation errors if any -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Edit Order Form -->
    <form action="{{ route('orders.update', $order->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Customer Name -->
        <div class="mb-3">
            <label for="customer_name" class="form-label">Customer Name</label>
            <input type="text" class="form-control" id="customer_name" name="customer_name" 
                   value="{{ old('customer_name', $order->customer_name) }}" required>
        </div>

        <!-- Products and Quantities -->
        <div class="mb-3">
            <label for="products" class="form-label">Products</label>
            @foreach ($products as $product)
                <div class="form-group">
                    <input type="checkbox" name="product_ids[]" value="{{ $product->id }}"
                           {{ isset($orderProducts[$product->id]) ? 'checked' : '' }}>
                    {{ $product->name }}

                    <input type="number" name="quantities[]" 
                           value="{{ old('quantities.' . $loop->index, $orderProducts[$product->id] ?? 1) }}" 
                           placeholder="Quantity" min="1">
                </div>
            @endforeach
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Update Order</button>
    </form>
</div>
@endsection
