<a href="#" onclick="goBack()"
    class="w-10 h-10 bg-white rounded-lg shadow-sm flex items-center justify-center hover:bg-gray-50 transition">
    <i class="fas fa-arrow-left text-gray-700"></i>
</a>

<script>
    function goBack() {
        const previousPage = window.getPreviousPage ? window.getPreviousPage() : '{{ route("dashboard") }}';
        const currentPage = window.location.pathname;

        // If previous page is same as current (shouldn't happen), go to dashboard
        if (previousPage === currentPage) {
            window.location.href = '{{ route("dashboard") }}';
        } else {
            // Try to go back in browser history
            if (window.history.length > 1) {
                window.history.back();
            } else {
                // Fallback to dashboard if no history
                window.location.href = '{{ route("dashboard") }}';
            }
        }
        return false;
    }
</script>