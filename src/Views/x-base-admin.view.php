<!DOCTYPE html>
<html lang="en">

<head>
    <title :if="isset($title)">{{ $title }} | Cyclone Administration</title>
    <title :else>Cyclone Administration</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png" />
    <link rel="manifest" href="/favicon/site.webmanifest" />

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <x-slot name="styles" />
    <x-vite-tags />

</head>


<body class="flex flex-col min-h-screen w-full">
    <div x-data="{
        scrolled: false,
        mobileMenuOpen: false,
        dropdowns: {
            about: false,
            portfolio: false,
            services: false,
            contact: false
        }
    }"
        x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 10 })"
        class="relative">
        <x-menu />
        <!-- Overlay for when dropdown is active -->
        <div
            x-show="Object.values(dropdowns).some(val => val === true)"
            class="fixed inset-0 bg-black/20 backdrop-blur-sm z-30"
            @click="Object.keys(dropdowns).forEach(key => dropdowns[key] = false)">
        </div>
    </div>
    <main class="pt-16 flex-grow">
        <x-slot />
    </main>
    <x-footer />

</body>

</html>