@extends('layouts.user.app')

@section('content')

    <!-- Page Title -->
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Customer Service Center</h2>
        <p class="text-gray-600 text-sm">Online Customer Service Time</p>
    </div>

    <!-- Customer Service Illustration -->
    <div class="mb-8 flex justify-center">
        <div class="bg-white rounded-3xl shadow-xl p-8 max-w-sm w-full">
            <div class="flex flex-col items-center">
                <!-- Illustration Placeholder -->
                <div class="mb-6 relative">
                    <div
                        class="w-64 h-64 bg-gradient-to-br from-blue-50 to-indigo-100 rounded-2xl flex items-center justify-center">
                        <div class="text-center">
                            <div
                                class="inline-flex items-center justify-center w-32 h-32 bg-gradient-to-br from-orange-400 to-orange-600 rounded-full mb-4 shadow-xl">
                                <i class="fas fa-headset text-white text-5xl"></i>
                            </div>
                            <div class="flex items-center justify-center gap-2 mb-2">
                                <i class="fas fa-book text-indigo-600 text-2xl"></i>
                                <i class="fas fa-coffee text-orange-600 text-xl"></i>
                                <i class="fas fa-laptop text-blue-600 text-3xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Customer Service Center</h3>
                    <p class="text-gray-600 text-sm">Online Customer Service Time</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Service Options -->
    <div class="space-y-3">
        <!-- Telegram Care Center -->
        <a href="#" class="block bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-200">
            <div class="p-5 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div
                        class="bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl w-12 h-12 flex items-center justify-center shadow-md">
                        <i class="fab fa-telegram-plane text-white text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Telegram Care Center</h3>
                        <p class="text-gray-500 text-sm">Contact us on Telegram</p>
                    </div>
                </div>
                <i class="fas fa-chevron-right text-gray-400 text-xl"></i>
            </div>
        </a>

        <!-- WhatsApp Care Center -->
        <a href="#" class="block bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-200">
            <div class="p-5 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div
                        class="bg-gradient-to-br from-green-400 to-green-600 rounded-xl w-12 h-12 flex items-center justify-center shadow-md">
                        <i class="fab fa-whatsapp text-white text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">WhatsApp Care Center</h3>
                        <p class="text-gray-500 text-sm">Contact us on WhatsApp</p>
                    </div>
                </div>
                <i class="fas fa-chevron-right text-gray-400 text-xl"></i>
            </div>
        </a>

        <!-- Help -->
        <a href="{{ route('help.index') }}"
            class="block bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-200">
            <div class="p-5 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div
                        class="bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl w-12 h-12 flex items-center justify-center shadow-md">
                        <i class="fas fa-question-circle text-white text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Help</h3>
                        <p class="text-gray-500 text-sm">FAQ and support</p>
                    </div>
                </div>
                <i class="fas fa-chevron-right text-gray-400 text-xl"></i>
            </div>
        </a>
    </div>

    <!-- Info Card -->
    <div class="mt-6 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-5 border-l-4 border-blue-500">
        <div class="flex items-start gap-3">
            <div class="bg-blue-500 rounded-full w-8 h-8 flex items-center justify-center flex-shrink-0 mt-0.5">
                <i class="fas fa-info text-white text-sm"></i>
            </div>
            <div>
                <h4 class="font-bold text-gray-900 mb-2">Need Assistance?</h4>
                <p class="text-gray-700 text-sm leading-relaxed">
                    Our customer service team is available 24/7 to help you with any questions or issues. Choose your
                    preferred communication method above to get started.
                </p>
            </div>
        </div>
    </div>

@endsection