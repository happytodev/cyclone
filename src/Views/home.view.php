<x-base>
    <main class="w-screen overflow-hidden bg-sky-100/20">
        <div class="relative isolate px-6 lg:px-8 flex flex-col items-center justify-center h-full">
            <!-- Background gradient -->
            <div class="pointer-events-none absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80" aria-hidden="true">
                <div
                    class="relative left-[calc(50%-11rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-[#7fbdea] to-[#9980fc] opacity-20 sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]"
                    style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
            </div>
            <!-- Bottom gradient -->
            <div class="pointer-events-none absolute inset-x-0 top-[calc(100%-13rem)] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[calc(100%-30rem)]" aria-hidden="true">
                <div class="relative left-[calc(50%+3rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 bg-gradient-to-tr from-[#7fbdea] to-[#9980fc] opacity-20 sm:left-[calc(50%+36rem)] sm:w-[72.1875rem]" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
            </div>
            <!-- Hero section -->
            <div class="mx-auto max-w-2xl py-32 sm:py-48 lg:py-8">
                <div class="text-center">
                    <div class="flex justify-center mb-10">
                        <img src="/img/logo.webp" alt="Logo" class="h-48">
                    </div>
                    <!-- Text -->
                    <h1 class="text-balance text-5xl font-semibold tracking-tight text-gray-800 sm:text-7xl">Cyclone</h1>
                    <p class="mt-8 text-pretty text-lg font-medium text-gray-500 sm:text-xl/8">The firstly blog engine based on Tempest.</p>
                    <!-- CTAs -->
                    <div class="mt-10 flex flex-col sm:flex-row gap-y-4 items-center justify-center gap-x-6">
                        <a href="https://www.github.com/happytodev/cyclone" target="_blank" class="rounded-md bg-sky-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-sky-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-600">Documentation</a>
                        <a href="https://discord.gg/vHTVPckYDP" class="text-sm/6 font-semibold text-gray-900 focus-visible:outline-none focus-visible:underline focus-visible:underline-offset-4 focus-visible:decoration-gray-300">Join our Discord
                            <span aria-hidden="true">→</span></a>
                    </div>
                </div>
            </div>

            <!-- Services Overview Section -->
            <section class="py-16">
                <div class="container mx-auto px-4">
                    <div class="max-w-7xl mx-auto">
                        <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Why you should be interested in it?</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Service 2 -->
                            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
                                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                                    <!-- <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path>
                                    </svg> -->
                                    <x-icon name="hugeicons:frameworks" class="size-8 text-indigo-400" />
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-2">Based on Tempest</h3>
                                <p class="text-gray-600">Cyclone is build on top of the framework that gets out of your way.</p>
                            </div>
                            <!-- Service 1 -->
                            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
                                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                                    <x-icon name="bi:markdown" class="size-8 text-indigo-400" />
                                    <!-- <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                    </svg> -->
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-2">Markdown compliant</h3>
                                <p class="text-gray-600">Create your content directly with markdown syntax. Happy to geek ;-)</p>
                            </div>
                            <!-- Service 1 -->
                            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
                                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                                    <x-icon name="tabler:brand-open-source" class="size-8 text-indigo-400" />
                                    <!-- <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                    </svg> -->
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-2">Open source</h3>
                                <p class="text-gray-600">Create your content directly with markdown syntax. Happy to geek ;-)</p>
                            </div>
                            
                            
                            <!-- Service 3 -->
                            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
                                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                                    <x-icon name="hugeicons:binoculars" class="size-8 text-indigo-400" />
                                    <!-- <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg> -->
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-2">A lot more to come</h3>
                                <p class="text-gray-600">Stay tune.</p>
                            </div>

                        </div>
                    </div>
                </div>
            </section>

            <!-- Latest news Section -->
            <section class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-12 ">
                <div class="container mx-auto px-4">
                    <div class="max-w-7xl mx-auto">
                        <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Latest news</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            <!-- Last 3 posts -->
                            <x-post :foreach="$posts as $lastnew" >
                                <x-card />
                            </x-post>
                            <x-post :forelse>
                                <p class="text-center text-gray-600 mt-8">It's quite empty here…</p>
                            </x-post>
                        </div>
                    </div>
                </div>
            </section>

        </div>



    </main>
</x-base>