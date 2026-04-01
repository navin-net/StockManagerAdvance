<footer class="bg-gray-900 text-gray-300 py-12 mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">

            <!-- Column 1: Logo & About -->
            <div class="col-span-1 md:col-span-2">
                <a href="{{ url('/') }}" class="text-2xl font-bold text-white hover:text-indigo-400 transition-colors">
                    E-Shop
                </a>
                <p class="mt-4 text-gray-400 max-w-md">
                    Your trusted online shopping destination for quality products at great prices.
                </p>
            </div>

            <!-- Column 2: Quick Links -->
            <div>
                <h3 class="text-white font-semibold mb-4">Quick Links</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ url('/') }}" class="hover:text-white transition-colors">Home</a></li>
                    <li><a href="{{ route('shop.products') }}" class="hover:text-white transition-colors">All Products</a></li>
                    <li><a href="{{ route('shop.cart') }}" class="hover:text-white transition-colors">Cart</a></li>
                    <li><a href="{{ route('shop.checkout') }}" class="hover:text-white transition-colors">Checkout</a></li>
                </ul>
            </div>

            <!-- Column 3: Support -->
            <div>
                <h3 class="text-white font-semibold mb-4">Support</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="hover:text-white transition-colors">Contact Us</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Shipping Policy</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Return Policy</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">FAQ</a></li>
                </ul>
            </div>

        </div>

        <!-- Bottom Bar -->
        <div class="border-t border-gray-800 mt-12 pt-8 flex flex-col md:flex-row justify-between items-center text-sm">
            <p class="text-gray-400">
                &copy; <span id="current-year"></span> E-Shop. All rights reserved.
            </p>

            <div class="flex gap-6 mt-4 md:mt-0">
                <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
                <a href="#" class="hover:text-white transition-colors">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>

<script>
    document.getElementById('current-year').textContent = new Date().getFullYear();


    const mobileBtn = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');

    mobileBtn.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');

        // Optional: change icon to X when open
        const isOpen = !mobileMenu.classList.contains('hidden');
        mobileBtn.innerHTML = isOpen
            ? `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6h12v12" />
               </svg>`
            : `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
               </svg>`;
    });
</script>
