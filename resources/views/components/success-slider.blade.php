<div
    class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-200 overflow-hidden shadow-sm mt-2">
    <div class="swiper successSwiper h-14">
        <div class="swiper-wrapper">
        </div>
    </div>
</div>

@push('scripts')
    <script>
        const firstNames = ['John', 'Sarah', 'Mike', 'Emma', 'David', 'Lisa', 'Tom', 'Anna', 'James', 'Maria',
            'Robert', 'Jennifer', 'Michael', 'Linda', 'William', 'Patricia', 'Richard', 'Susan',
            'Joseph', 'Jessica', 'Thomas', 'Karen', 'Charles', 'Nancy', 'Daniel', 'Betty'];

        function generateRandomSlide() {
            const name = firstNames[Math.floor(Math.random() * firstNames.length)];
            const maskLength = Math.floor(Math.random() * 5) + 4;
            const maskedName = '*'.repeat(maskLength) + name.slice(-2);
            const action = Math.random() > 0.5 ? 'Withdrawal' : 'Earning';
            const amount = (Math.floor(Math.random() * 1950) + 50).toFixed(2);

            // FIX: Added 'h-full' and flex centering to ensure slide fits the container
            return `
                        <div class="swiper-slide h-full">
                            <div class="flex items-center h-full w-full gap-3 px-4 sm:px-6 mx-2 sm:mx-4">
                                <i class="fas fa-user-circle text-green-500 text-xl sm:text-2xl"></i>
                                <div class="leading-tight">
                                    <div class="font-semibold text-gray-800 text-sm sm:text-base">${maskedName}</div>
                                    <div class="text-xs sm:text-sm text-green-700">
                                        <i class="fas fa-check-circle"></i>
                                        Successful ${action} ${amount} USDT
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
        }

        function initializeSuccessSwiper() {
            const swiperWrapper = document.querySelector('.successSwiper .swiper-wrapper');

            for (let i = 0; i < 10; i++) {
                swiperWrapper.innerHTML += generateRandomSlide();
            }

            const swiper = new Swiper('.successSwiper', {
                direction: 'vertical',
                slidesPerView: 1, // Ekbare ekta slide dekhabe
                loop: true,
                allowTouchMove: false,
                autoplay: {
                    delay: 2000,
                    disableOnInteraction: false,
                    // reverseDirection removed for standard bottom-to-top flow
                },
                speed: 800,
            });

            setInterval(() => {
                swiper.appendSlide(generateRandomSlide());
                // Memory management: remove old slides if too many
                if (swiper.slides.length > 20) {
                    swiper.removeSlide(0);
                }
            }, 4000);
        }

        document.addEventListener('DOMContentLoaded', initializeSuccessSwiper);
    </script>
@endpush