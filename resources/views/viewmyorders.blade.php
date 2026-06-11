<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('User Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif;">
                        <thead>
                            <tr style="background-color: #f2f2f2;">
                                <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                                    Customer Name
                                </th>
                                <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                                    Address
                                </th>
                                <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                                    Phone Number
                                </th>
                                <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                                    Product
                                </th>
                                <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                                    Product Price
                                </th>
                                <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                                    Product Image
                                </th>
                                <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">
                                    Status
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($orders as $order)
                                <tr style="border-bottom: 1px solid #ddd;">
                                    <td style="padding: 12px;">
                                        {{ $order->user->name }}
                                    </td>

                                    <td style="padding: 12px;">
                                        {{ $order->receiver_address }}
                                    </td>

                                    <td style="padding: 12px;">
                                        {{ $order->receiver_phone }}
                                    </td>

                                    <td style="padding: 12px;">
                                        {{ $order->product->product_title }}
                                    </td>

                                    <td style="padding: 12px;">
                                        {{ $order->product->product_prices }}
                                    </td>

                                    <td style="padding: 12px;">
                                        <img
                                            src="{{ asset('products/' . $order->product->product_image) }}"
                                            style="width: 150px;"
                                            alt="Product Image"
                                        >
                                    </td>
                                    <td style="padding: 12px;">
                                        {{$order->status}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>