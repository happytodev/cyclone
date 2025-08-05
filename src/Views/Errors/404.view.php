<x-base>
    <div class="flex flex-col justify-center items-center bg-pink-900 min-w-screen min-h-screen text-[#a8caf7] antialiased">
        <div class="px-8 container">
            <h1 class="font-thin text-8xl flex gap-x-1">
                <span class="text-pink-700">HTTP</span>
                <span class="text-pink-500">{{ $status }}</span>
            </h1>
            <h4>
                <span class="text-2xl uppercase text-pink-200">{{ $message }}</span>
            </h4>
        </div>
    </div>
</x-base>