        <!-- Footer -->
        <footer class="bg-gray-800 text-white py-12">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <!-- Company Info -->
                    <div>
                        <x-logo />
                        <p class="text-gray-400 mb-4">The first blog engine based on Tempest.</p>
                        <div class="flex space-x-4">
                            <a href="https://x.com/happytodev" target="_blank" class="text-gray-400 hover:text-white transition-colors">
                                <x-icon name="line-md:twitter-x" class="size-4 text-sky-400" />
                            </a>
                            <a href="https://bsky.app/profile/happytodev.bsky.social" target="_blank" class="text-gray-400 hover:text-white transition-colors">
                                <x-icon name="hugeicons:bluesky" class="size-4 text-sky-400" />
                            </a>
                            <a href="https://www.linkedin.com/company/happytodev/" target="_blank" class="text-gray-400 hover:text-white transition-colors">
                                <x-icon name="streamline:linkedin" class="size-4 text-sky-400" />
                            </a>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                        <ul class="space-y-2">
                            <li><a href="/" class="text-gray-400 hover:text-white transition-colors">Home</a></li>
                            <li><a href="/blog" class="text-gray-400 hover:text-white transition-colors">Blog</a></li>
                            <li><a href="/about-us" class="text-gray-400 hover:text-white transition-colors">About Us</a></li>
                        </ul>
                    </div>

                    <!-- Services -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Services</h3>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-gray-400 hover:text-white transition-colors">PHP Development</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Tempest Development</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Laravel Development</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Courses</a></li>
                        </ul>
                    </div>

                    <!-- Contact Info -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Contact Us</h3>
                        <ul class="space-y-2">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-blue-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="text-gray-400">GRASSE, France</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-blue-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                <span class="text-gray-400">+33 7 00 00 00 00</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-blue-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-gray-400">happytodev@gmail.com</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="border-t border-gray-700 mt-10 pt-6 text-center text-gray-400">
                    <p>&copy; 2025 Made by Happytodev. All rights reserved.</p>
                </div>
            </div>
        </footer>

        <script>
            // Additional initialization if needed
            document.addEventListener('alpine:init', () => {
                // Any additional Alpine.js initialization can go here
            });
        </script>