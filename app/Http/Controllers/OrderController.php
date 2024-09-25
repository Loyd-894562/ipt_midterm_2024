<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Display a listing of orders
    public function index()
    {
        $orders = Order::with('products')->get(); // Fetch all orders with their associated products
        return view('orders.index', compact('orders'));
    }

    // Show the form for creating a new order
    public function create()
    {
        $products = Product::all(); // Get all products for selection
        return view('orders.create', compact('products'));
    }

    // Store a newly created order in the database
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
            'quantities' => 'required|array',
            'quantities.*' => 'integer|min:1' // Ensure quantities are valid
        ]);

        // Create a new order with customer name
        $order = Order::create([
            'customer_name' => $request->customer_name,
        ]);

        // Prepare product and quantity data for the pivot table
        $productsWithQuantities = [];
        foreach ($request->product_ids as $key => $productId) {
            $productsWithQuantities[$productId] = ['quantity' => $request->quantities[$key]];
        }

        // Attach products to the order with their quantities
        $order->products()->sync($productsWithQuantities);

        return redirect()->route('orders.index')->with('success', 'Order created successfully.');
    }

    // Show the form for editing an order
    public function edit(Order $order)
    {
        $products = Product::all(); // Get all products
        $orderProducts = $order->products->pluck('pivot.quantity', 'id')->toArray(); // Get products with quantities

        return view('orders.edit', compact('order', 'products', 'orderProducts'));
    }

    // Update the specified order in the database
    public function update(Request $request, Order $order)
    {
        // Validation
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
            'quantities' => 'required|array',
            'quantities.*' => 'integer|min:1' // Ensure quantities are valid
        ]);

        // Update the order's customer name
        $order->update([
            'customer_name' => $request->customer_name,
        ]);

        // Prepare product and quantity data for the pivot table
        $productsWithQuantities = [];
        foreach ($request->product_ids as $key => $productId) {
            $productsWithQuantities[$productId] = ['quantity' => $request->quantities[$key]];
        }

        // Update the order's products and their quantities
        $order->products()->sync($productsWithQuantities);

        return redirect()->route('orders.index')->with('success', 'Order updated successfully.');
    }

    // Remove the specified order from the database
    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
    }
}
