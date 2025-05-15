<!-- Fixed Header with Blur Effect inspired by Maxim https://tailwindflex.com/@maximus/dropdown-blur-menu -->
<?php //dd($lastnews); 
?>
<header x-bind:class="{ 
            'fixed top-0 left-0 right-0 transition-all duration-300 z-40 blur-bg shadow-lg': true,
            'bg-white/70': scrolled,
            'bg-white': !scrolled
        }">
    <div class="container mx-auto px-4 py-3">
        <div class="flex justify-between items-center">
            <!-- Logo -->
            <x-logo />

            <!-- Mobile Menu Button -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden block text-gray-600 focus:outline-none">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                    <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

            <!-- Desktop Menu -->
            <nav class="hidden lg:flex space-x-8 items-center">


                <!-- Portfolio -->
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open; dropdowns.portfolio = !dropdowns.portfolio"
                        class="flex items-center text-gray-700 hover:text-sky-600 focus:outline-none">
                        <span>Our last news</span>
                        <svg x-bind:class="{'rotate-180': open}" class="ml-1 h-4 w-4 transition-transform" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <!-- Portfolio Dropdown with Card Layout -->
                    <template x-if="open">
                        <div
                            class="dropdown-content portfolio-dropdown mt-2 py-6 px-4 bg-white/80 rounded-md shadow-xl blur-bg"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 transform scale-95"
                            x-transition:enter-end="opacity-100 transform scale-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 transform scale-100"
                            x-transition:leave-end="opacity-0 transform scale-95"
                            style="display: block;">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
                                <x-post :foreach="$lastnews as $post">
                                    <x-card />
                                </x-post>
                                <x-post :forelse>
                                    <p class="text-center text-gray-600 mt-8">It's quite empty here…</p>
                                </x-post>

                            </div>
                        </div>
                    </template>
                </div>

                <!-- About Us -->
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open; dropdowns.about = !dropdowns.about"
                        class="flex items-center text-gray-700 hover:text-sky-600 focus:outline-none">
                        <span>About Us</span>
                        <svg x-bind:class="{'rotate-180': open}" class="ml-1 h-4 w-4 transition-transform" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <!-- About Us Dropdown -->
                    <template x-if="open">
                        <div
                            class="dropdown-content absolute right-0 mt-2 py-2 bg-white/80 rounded-md shadow-xl blur-bg"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 transform scale-95"
                            x-transition:enter-end="opacity-100 transform scale-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 transform scale-100"
                            x-transition:leave-end="opacity-0 transform scale-95"
                            style="display: block;">
                            <a href="#" class="block px-4 py-2 text-gray-700 hover:text-sky-600 hover:bg-sky-50">Our History</a>
                            <a href="#" class="block px-4 py-2 text-gray-700 hover:text-sky-600 hover:bg-sky-50">Our Team</a>
                        </div>
                    </template>
                </div>

                <!-- Services -->
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open; dropdowns.services = !dropdowns.services"
                        class="flex items-center text-gray-700 hover:text-sky-600 focus:outline-none">
                        <span>Services</span>
                        <svg x-bind:class="{'rotate-180': open}" class="ml-1 h-4 w-4 transition-transform" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <!-- Services Dropdown -->
                    <template x-if="open">
                        <div
                            class="dropdown-content absolute right-0 mt-2 py-2 bg-white/80 rounded-md shadow-xl blur-bg"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 transform scale-95"
                            x-transition:enter-end="opacity-100 transform scale-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 transform scale-100"
                            x-transition:leave-end="opacity-0 transform scale-95"
                            style="display: block;">
                            <a href="#" class="block px-4 py-2 text-gray-700 hover:text-sky-600 hover:bg-sky-50">PHP Development</a>
                            <a href="#" class="block px-4 py-2 text-gray-700 hover:text-sky-600 hover:bg-sky-50">Tempest Development</a>
                            <a href="#" class="block px-4 py-2 text-gray-700 hover:text-sky-600 hover:bg-sky-50">Laravel Development</a>
                            <a href="#" class="block px-4 py-2 text-gray-700 hover:text-sky-600 hover:bg-sky-50">Courses</a>
                        </div>
                    </template>
                </div>

                <!-- Contact Us -->
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open; dropdowns.contact = !dropdowns.contact"
                        class="flex items-center text-gray-700 hover:text-sky-600 focus:outline-none">
                        <span>Contact Us</span>
                        <svg x-bind:class="{'rotate-180': open}" class="ml-1 h-4 w-4 transition-transform" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <!-- Contact Us Dropdown -->
                    <template x-if="open">
                        <div
                            class="dropdown-content absolute right-0 mt-2 py-2 bg-white/80 rounded-md shadow-xl blur-bg"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 transform scale-95"
                            x-transition:enter-end="opacity-100 transform scale-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 transform scale-100"
                            x-transition:leave-end="opacity-0 transform scale-95"
                            style="display: block;">
                            <a href="mailto:happytodev@gmail.com" class="block px-4 py-2 text-gray-700 hover:text-sky-600 hover:bg-sky-50">Email Us</a>
                            <a href="tel:+33700000000" class="block px-4 py-2 text-gray-700 hover:text-sky-600 hover:bg-sky-50">Call Us</a>
                        </div>
                    </template>
                </div>
            </nav>
        </div>
    </div>

    <!-- Mobile Menu (Hidden by default) -->
    <div
        x-show="mobileMenuOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform -translate-y-10"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform -translate-y-10"
        class="lg:hidden bg-white/80 blur-bg py-2 shadow-lg">
        <!-- About Us Mobile -->
        <div class="px-4 py-2">
            <button @click="dropdowns.about = !dropdowns.about" class="flex justify-between items-center w-full text-gray-700 focus:outline-none">
                <span class="font-medium">About Us</span>
                <svg x-bind:class="{'rotate-180': dropdowns.about}" class="h-4 w-4 transition-transform" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
            <div x-show="dropdowns.about" class="mt-2 pl-4 border-l-2 border-sky-500">
                <a href="#" class="block py-2 text-gray-700 hover:text-sky-600">Our History</a>
                <a href="#" class="block py-2 text-gray-700 hover:text-sky-600">Our Team</a>
                <a href="#" class="block py-2 text-gray-700 hover:text-sky-600">Mission & Vision</a>
                <a href="#" class="block py-2 text-gray-700 hover:text-sky-600">Careers</a>
            </div>
        </div>

        <!-- Portfolio Mobile -->
        <div class="px-4 py-2">
            <button @click="dropdowns.portfolio = !dropdowns.portfolio" class="flex justify-between items-center w-full text-gray-700 focus:outline-none">
                <span class="font-medium">Portfolio</span>
                <svg x-bind:class="{'rotate-180': dropdowns.portfolio}" class="h-4 w-4 transition-transform" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
            <div x-show="dropdowns.portfolio" class="mt-2 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Mobile Portfolio cards (limited display) -->
                    <a href="#" class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        <div class="relative h-36 overflow-hidden">
                            <img src="https://picsum.photos/200/400" alt="Project 1" class="w-full h-full object-cover">
                        </div>
                        <div class="p-3">
                            <h3 class="text-sm font-semibold text-gray-800">Modern Office Design</h3>
                            <p class="text-sm text-sky-600 font-medium">$2,500</p>
                        </div>
                    </a>
                    <a href="#" class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        <div class="relative h-36 overflow-hidden">
                            <img src="https://picsum.photos/200/400" alt="Project 2" class="w-full h-full object-cover">
                        </div>
                        <div class="p-3">
                            <h3 class="text-sm font-semibold text-gray-800">Luxury Apartment</h3>
                            <p class="text-sm text-sky-600 font-medium">$5,800</p>
                        </div>
                    </a>
                </div>
                <a href="#" class="block w-full py-2 text-center text-sky-600 font-medium hover:underline">View All Projects</a>
            </div>
        </div>

        <!-- Services Mobile -->
        <div class="px-4 py-2">
            <button @click="dropdowns.services = !dropdowns.services" class="flex justify-between items-center w-full text-gray-700 focus:outline-none">
                <span class="font-medium">Services</span>
                <svg x-bind:class="{'rotate-180': dropdowns.services}" class="h-4 w-4 transition-transform" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
            <div x-show="dropdowns.services" class="mt-2 pl-4 border-l-2 border-sky-500">
                <a href="#" class="block py-2 text-gray-700 hover:text-sky-600">Interior Design</a>
                <a href="#" class="block py-2 text-gray-700 hover:text-sky-600">Architectural Planning</a>
                <a href="#" class="block py-2 text-gray-700 hover:text-sky-600">3D Visualization</a>
                <a href="#" class="block py-2 text-gray-700 hover:text-sky-600">Renovation Consulting</a>
                <a href="#" class="block py-2 text-gray-700 hover:text-sky-600">Furniture Selection</a>
            </div>
        </div>

        <!-- Contact Us Mobile -->
        <div class="px-4 py-2">
            <button @click="dropdowns.contact = !dropdowns.contact" class="flex justify-between items-center w-full text-gray-700 focus:outline-none">
                <span class="font-medium">Contact Us</span>
                <svg x-bind:class="{'rotate-180': dropdowns.contact}" class="h-4 w-4 transition-transform" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
            <div x-show="dropdowns.contact" class="mt-2 pl-4 border-l-2 border-sky-500">
                <a href="#" class="block py-2 text-gray-700 hover:text-sky-600">Email Us</a>
                <a href="#" class="block py-2 text-gray-700 hover:text-sky-600">Call Us</a>
                <a href="#" class="block py-2 text-gray-700 hover:text-sky-600">Office Locations</a>
                <a href="#" class="block py-2 text-gray-700 hover:text-sky-600">Request a Quote</a>
            </div>
        </div>
    </div>
</header>