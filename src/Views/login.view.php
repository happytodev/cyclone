<x-base>

<div class="flex min-h-screen items-center justify-center bg-gray-50">
    <div class="w-full max-w-md space-y-6">
        <h1 class="text-center text-2xl font-semibold text-gray-900">Connexion</h1>
        <div class="flex flex-col gap-4">
            <a href="/auth/github" class="flex items-center justify-center gap-3 rounded-md bg-black px-4 py-2 text-white hover:bg-gray-700 transition">
                <img src="/img/github.svg" alt="GitHub" class="h-5 w-5">
                Connexion avec GitHub
            </a>
            <a href="/auth/amazon" class="flex items-center justify-center gap-3 rounded-md bg-yellow-400 px-4 py-2 text-gray-900 hover:bg-yellow-500 transition">
                <img src="/img/amazon.svg" alt="Amazon" class="h-5 w-5">
                Connexion avec Amazon
            </a>
        </div>
    </div>
</div>
</x-base>