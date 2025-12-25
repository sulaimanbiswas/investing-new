<div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-200 overflow-hidden shadow-sm mt-2">
    <div class="swiper successSwiper h-16">
        <div class="swiper-wrapper">
            </div>
    </div>
</div>

@push('scripts')
    <script>
        // Random Data Arrays
        const firstNames = ['John', 'Sarah', 'Mike', 'Emma', 'David', 'Lisa', 'Tom', 'Anna', 'James', 'Maria',
            'Robert', 'Jennifer', 'Michael', 'Linda', 'William', 'Patricia', 'Richard', 'Susan',
            'Joseph', 'Jessica', 'Thomas', 'Karen', 'Charles', 'Nancy', 'Daniel', 'Betty'];

        function generateRandomSlide() {
            const name = firstNames[Math.floor(Math.random() * firstNames.length)];
            const maskLength = Math.floor(Math.random() * 5) + 4;
            const maskedName = '*'.repeat(maskLength) + name.slice(-2);
            const action = Math.random() > 0.5 ? 'Withdrawal' : 'Earning';
            const amount = (Math.floor(Math.random() * 4950) + 50).toFixed(2);

            return `
                <div class="swiper-slide w-full h-full flex items-center">
                    <div class="flex items-center w-full gap-3 bg-white/90 backdrop-blur px-4 sm:px-6 py-2 sm:py-3 rounded-lg shadow-sm mx-2 sm:mx-4">
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

            // 1. Generate initial buffer (More slides initially for smoothness)
            for (let i = 0; i < 10; i++) {
                swiperWrapper.innerHTML += generateRandomSlide();
            }

            // 2. Initialize Swiper with Event Listener
            const swiper = new Swiper('.successSwiper', {
                direction: 'vertical',
                slidesPerView: 1,
                loop: false, // FIX 2: Loop false rakhte hobe dynamic update er jonno
                allowTouchMove: false,
                observer: true,
                observeParents: true,
                autoplay: {
                    delay: 3000, // 3 seconds por por change hobe
                    disableOnInteraction: false,
                },
                speed: 800,
                // FIX 3: Use 'on' event instead of setInterval
                on: {
                    slideChange: function () {
                        // Check if we are near the end (last 3 slides)
                        if (this.activeIndex >= this.slides.length - 3) {
                            // Append new random slide automatically
                            this.appendSlide(generateRandomSlide());
                            
                            // Optional: Keep memory clean (Remove old slides if list gets too big)
                            if (this.slides.length > 50) {
                                this.removeSlide(0);
                                // Adjust index so user doesn't notice the removal
                                this.slideTo(this.activeIndex - 1, 0); 
                            }
                        }
                    }
                }
            });
        }

        document.addEventListener('DOMContentLoaded', initializeSuccessSwiper);
    </script>
@endpush