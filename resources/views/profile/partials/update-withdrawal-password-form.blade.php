<section>
    <form method="post" action="{{ route('profile.update-withdrawal-password') }}" class="space-y-5">
        @csrf
        @method('put')

        <!-- Current Withdrawal Password -->
        <div>
            <label for="current_withdrawal_password" class="block text-sm font-medium text-gray-700 mb-1">
                {{ __('ui.current_withdrawal_password') }}
            </label>
            <input id="current_withdrawal_password" name="current_withdrawal_password" type="password"
                class="w-full rounded-lg border-gray-200 focus:border-rose-500 focus:ring-rose-500"
                placeholder="{{ __('ui.enter_current_withdrawal_password') }}" required>
            @error('current_withdrawal_password', 'updateWithdrawalPassword')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- New Withdrawal Password -->
        <div>
            <label for="withdrawal_password" class="block text-sm font-medium text-gray-700 mb-1">
                {{ __('ui.new_withdrawal_password') }}
            </label>
            <input id="withdrawal_password" name="withdrawal_password" type="password"
                class="w-full rounded-lg border-gray-200 focus:border-rose-500 focus:ring-rose-500"
                placeholder="{{ __('ui.enter_new_withdrawal_password') }}" required>
            @error('withdrawal_password', 'updateWithdrawalPassword')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Withdrawal Password -->
        <div>
            <label for="withdrawal_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                {{ __('ui.confirm_withdrawal_password') }}
            </label>
            <input id="withdrawal_password_confirmation" name="withdrawal_password_confirmation" type="password"
                class="w-full rounded-lg border-gray-200 focus:border-rose-500 focus:ring-rose-500"
                placeholder="{{ __('ui.confirm_new_withdrawal_password') }}" required>
            @error('withdrawal_password_confirmation', 'updateWithdrawalPassword')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center gap-3">
            <button type="submit"
                class="px-6 py-2.5 bg-rose-600 text-white text-sm font-semibold rounded-lg hover:bg-rose-700 transition-colors shadow-sm">
                {{ __('ui.update_withdrawal_password') }}
            </button>

            @if (session('status') === 'withdrawal-password-updated')
                <p class="text-sm text-green-600 font-medium flex items-center gap-1">
                    <i class="fas fa-check-circle"></i> {{ __('ui.saved_successfully') }}
                </p>
            @endif
        </div>
    </form>
</section>