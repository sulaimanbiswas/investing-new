@extends('layouts.user.app')

@section('content')

    <!-- Page Title -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Help</h2>
        <p class="text-gray-600 text-sm">Frequently asked questions and guides</p>
    </div>

    <!-- Help Topics -->
    <div class="space-y-4">

        <!-- 1. About recharge -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <button type="button" class="w-full p-5 text-left flex items-center justify-between hover:bg-gray-50 transition"
                onclick="this.parentElement.querySelector('.help-content').classList.toggle('hidden')">
                <div class="flex items-center gap-3">
                    <div
                        class="bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg w-10 h-10 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-wallet text-white text-lg"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">1. About recharge</h3>
                </div>
                <i class="fas fa-chevron-down text-gray-400 transform transition-transform"></i>
            </button>
            <div class="help-content hidden border-t border-gray-100">
                <div class="p-5 bg-gradient-to-br from-blue-50 to-indigo-50">
                    <p class="text-gray-700 text-sm leading-relaxed mb-3">
                        You can top up your account in the "My"-"ye interface. Click the "Recharge button in the name of the
                        assignee and the amount will be "Transfered to. Use the account provided by the platform, please be
                        sure to submit screenshot of the successful, and be nure that your recharge is received quity they
                        may not match the amount you wish to recharge.
                    </p>
                    <p class="text-gray-700 text-sm leading-relaxed">
                        Be sure to double check the USOT account or bank account you entered for the depot be ipping up. The
                        platform will change the USDT allet account or bank accountme, so you have any
                    </p>
                </div>
            </div>
        </div>

        <!-- 2. About -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <button type="button" class="w-full p-5 text-left flex items-center justify-between hover:bg-gray-50 transition"
                onclick="this.parentElement.querySelector('.help-content').classList.toggle('hidden')">
                <div class="flex items-center gap-3">
                    <div
                        class="bg-gradient-to-br from-green-400 to-green-600 rounded-lg w-10 h-10 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-money-bill-wave text-white text-lg"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">2. About withdrawal</h3>
                </div>
                <i class="fas fa-chevron-down text-gray-400 transform transition-transform"></i>
            </button>
            <div class="help-content hidden border-t border-gray-100">
                <div class="p-5 bg-gradient-to-br from-green-50 to-emerald-50">
                    <p class="text-gray-700 text-sm leading-relaxed">
                        You want to apply for withdrawal you must compute 25 orders before you can apply, plead your with
                        und the "My"-"My during working touck the button then you want to red to your US work within 24.00
                        after you apply for
                    </p>
                </div>
            </div>
        </div>

        <!-- 3. About grabbing orders and freeing orders -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <button type="button" class="w-full p-5 text-left flex items-center justify-between hover:bg-gray-50 transition"
                onclick="this.parentElement.querySelector('.help-content').classList.toggle('hidden')">
                <div class="flex items-center gap-3">
                    <div
                        class="bg-gradient-to-br from-orange-400 to-orange-600 rounded-lg w-10 h-10 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-shopping-cart text-white text-lg"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">3. About grabbing orders and freeing orders</h3>
                </div>
                <i class="fas fa-chevron-down text-gray-400 transform transition-transform"></i>
            </button>
            <div class="help-content hidden border-t border-gray-100">
                <div class="p-5 bg-gradient-to-br from-orange-50 to-red-50">
                    <p class="text-gray-700 text-sm leading-relaxed">
                        After the account balance reaches the minimum 2050, you can grab the order and each person can grab
                        up to 25 orders per day! Click "Order"-"Automatic Order below and worthgate under the dress, you
                        need to complete task orders soon as possilbe so as not to delay the completion of subsequent 23:59,
                        and 25 tasks need to be completed again
                    </p>
                </div>
            </div>
        </div>

        <!-- 4. Platform Features -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <button type="button" class="w-full p-5 text-left flex items-center justify-between hover:bg-gray-50 transition"
                onclick="this.parentElement.querySelector('.help-content').classList.toggle('hidden')">
                <div class="flex items-center gap-3">
                    <div
                        class="bg-gradient-to-br from-purple-400 to-purple-600 rounded-lg w-10 h-10 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-star text-white text-lg"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">4. Platform Features</h3>
                </div>
                <i class="fas fa-chevron-down text-gray-400 transform transition-transform"></i>
            </button>
            <div class="help-content hidden border-t border-gray-100">
                <div class="p-5 bg-gradient-to-br from-purple-50 to-pink-50">
                    <ul class="space-y-2 text-gray-700 text-sm">
                        <li class="flex items-start gap-2">
                            <i class="fas fa-check-circle text-purple-600 mt-0.5"></i>
                            <span>VIP level system with different commission rates</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-check-circle text-purple-600 mt-0.5"></i>
                            <span>Daily order limits based on your VIP level</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-check-circle text-purple-600 mt-0.5"></i>
                            <span>Referral system with team commissions</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-check-circle text-purple-600 mt-0.5"></i>
                            <span>24/7 customer support via Telegram and WhatsApp</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- 5. Commission Rates -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <button type="button" class="w-full p-5 text-left flex items-center justify-between hover:bg-gray-50 transition"
                onclick="this.parentElement.querySelector('.help-content').classList.toggle('hidden')">
                <div class="flex items-center gap-3">
                    <div
                        class="bg-gradient-to-br from-indigo-400 to-indigo-600 rounded-lg w-10 h-10 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-percentage text-white text-lg"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">5. Commission Rates</h3>
                </div>
                <i class="fas fa-chevron-down text-gray-400 transform transition-transform"></i>
            </button>
            <div class="help-content hidden border-t border-gray-100">
                <div class="p-5 bg-gradient-to-br from-indigo-50 to-blue-50">
                    <p class="text-gray-700 text-sm leading-relaxed mb-3">
                        Earn commission on every completed order based on your platform level:
                    </p>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between p-3 bg-white rounded-lg">
                            <span class="font-semibold text-gray-900">VIP 1</span>
                            <span class="text-yellow-600 font-bold">2.0% - 4.0%</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-white rounded-lg">
                            <span class="font-semibold text-gray-900">VIP 2</span>
                            <span class="text-orange-600 font-bold">3.0% - 5.0%</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-white rounded-lg">
                            <span class="font-semibold text-gray-900">VIP 3</span>
                            <span class="text-red-600 font-bold">4.0% - 6.0%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Still Need Help Card -->
    <div class="mt-6 bg-gradient-to-br from-yellow-50 to-orange-50 rounded-2xl p-5 border-l-4 border-orange-500">
        <div class="flex items-start gap-3">
            <div class="bg-orange-500 rounded-full w-8 h-8 flex items-center justify-center flex-shrink-0 mt-0.5">
                <i class="fas fa-question text-white text-sm"></i>
            </div>
            <div>
                <h4 class="font-bold text-gray-900 mb-2">Still need help?</h4>
                <p class="text-gray-700 text-sm leading-relaxed mb-3">
                    If you couldn't find the answer you're looking for, our customer service team is here to help 24/7.
                </p>
                <a href="{{ route('service.index') }}"
                    class="inline-flex items-center gap-2 bg-gradient-to-r from-orange-500 to-orange-600 text-white px-4 py-2 rounded-lg font-semibold text-sm hover:from-orange-600 hover:to-orange-700 transition">
                    <i class="fas fa-headset"></i>
                    <span>Contact Support</span>
                </a>
            </div>
        </div>
    </div>

@endsection