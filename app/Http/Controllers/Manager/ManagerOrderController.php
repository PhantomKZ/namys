<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class ManagerOrderController extends Controller
{
    /**
     * Display a listing of the orders for the manager.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $orders = Order::whereIn('status', ['в обработке', 'в процессе обработки'])->get();
        return view('manager.orders.index', compact('orders'));
    }

    /**
     * Display the form for processing the specified order.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\View\View
     */
    public function process(Order $order)
    {
        $order->refresh(); // Перезагружаем модель, чтобы получить актуальные данные
        
        // Устанавливаем статус 'в процессе обработки' при открытии, если он еще 'в обработке'
        if ($order->status === 'в обработке') {
            $order->status = 'в процессе обработки';
            $order->save();
        }
        return view('manager.orders.process', compact('order'));
    }

    /**
     * Save changes to the specified order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(Request $request, Order $order)
    {
        Log::info('ManagerOrderController@save called', ['order_id' => $order->id, 'request_data' => $request->all()]);

        $request->validate([
            'client_phone' => ['nullable', 'string'],
            'shipping_address' => ['nullable', 'string'],
            'manager_comment' => ['nullable', 'string'],
        ]);

        Log::info('Validation passed', ['request_data' => $request->only(['client_phone', 'shipping_address', 'manager_comment'])]);

        $order->client_phone = $request->client_phone;
        $order->shipping_address = $request->shipping_address;
        $order->manager_comment = $request->manager_comment;

        // Статус уже должен быть 'в процессе обработки' благодаря методу process, но можно перестраховаться
        if ($order->status === 'в обработке') {
             $order->status = 'в процессе обработки';
             $order->save();
        }

        try {
            $order->save(); // Сохраняем обновленные данные (телефон, адрес, комментарий, если есть)
            Log::info('Order saved successfully', ['order_id' => $order->id]);
        } catch (\Exception $e) {
            Log::error('Error saving order', ['order_id' => $order->id, 'error' => $e->getMessage()]);
        }

        return redirect()->route('manager.orders.index')->with('success', 'Изменения в заказе сохранены.');
    }

    /**
     * Complete the processing of the specified order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function complete(Request $request, Order $order)
    {
        Log::info('ManagerOrderController@complete called', ['order_id' => $order->id, 'request_data' => $request->all()]);

        $request->validate([
            'client_phone' => ['nullable', 'string'],
            'shipping_address' => ['nullable', 'string'],
            'manager_comment' => ['nullable', 'string'],
        ]);

        $order->client_phone = $request->client_phone;
        $order->shipping_address = $request->shipping_address;
        $order->manager_comment = $request->manager_comment;

        $order->status = 'обработан';
        $order->save();

        return redirect()->route('manager.orders.all')->with('success', 'Заказ обработан и завершен.');
    }

    /**
     * Display a listing of all orders for the manager.
     *
     * @return \Illuminate\View\View
     */
    public function allOrders()
    {
        $orders = Order::withoutGlobalScopes()->get();
        Log::info('ManagerOrderController@allOrders called', ['orders_count' => $orders->count(), 'orders_statuses' => $orders->pluck('status')->toArray()]);
        return view('manager.orders.all', compact('orders'));
    }

    /**
     * Display the specified order for the manager.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\View\View
     */
    public function show(Order $order)
    {
        // Logic to show a specific order
        return view('manager.orders.show', compact('order'));
    }

    /**
     * Update the status of the specified order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => ['required', 'in:в обработке,в процессе обработки,обработан'],
        ]);

        $order->status = $request->status;
        $order->save();

        return redirect()->route('manager.orders.index')->with('success', 'Статус заказа обновлен успешно!');
    }
} 