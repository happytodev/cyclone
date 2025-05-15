<x-base title="Blog post">
    <div class="max-w-4xl mx-auto px-4 py-8">
        <!-- Article header -->
        <header class="mb-8">
            <h1 class="text-7xl font-bold text-gray-900">{{ $post->title }}</h1>
            <div class="mt-4 text-sm text-gray-500">
                <span> by {{ $post->user->name }}</span>
                <span :if="$this->post->published_at"> on {{ $post->published_at->format('M j, Y') }}</span>
            </div>
            <!-- TLDR section as a quote -->
            <div :if="$post->tldr" class="mt-4">
                <blockquote class="border-l-4 border-gray-300 pl-4 italic text-gray-600">
                    <span class="font-black">TLDR: </span> <span class="italic">{{ $post->tldr }}</span>
                </blockquote>
            </div>
        </header>

        <!-- Article content -->
        <section class="prose prose-lg">
            {!! $content !!}
        </section>

    </div>

</x-base>