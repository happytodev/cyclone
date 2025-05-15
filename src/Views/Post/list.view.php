<x-base title="Blog Posts">
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Latest Posts</h1>

        <!-- Post grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <x-post :foreach="$this->posts as $post">
                <x-card />
            </x-post>
            <x-post :forelse>
                <p class="text-center text-gray-600 mt-8">It's quite empty here…</p>
            </x-post>
        </div>


        <!-- Pagination -->
        <div class="mt-8 flex justify-center gap-2">
            <!-- ‘Previous’ button displayed if not on first page -->
            <div :if="$this->page > 1">
                <a href="/blog?page={{ $this->page - 1 }}" class="px-4 py-2 bg-sky-500 text-white rounded">Previous</a>
            </div>

            <!-- Loop over page numbers with :foreach -->
            <div :foreach="range(1, $this->totalPages) as $i">
                <a href="/blog?page={{ $i }}"
                    class="px-4 py-2 {{ $i == $this->page ? 'bg-sky-700' : 'bg-sky-500' }} text-white rounded">
                    {{ $i }}
                </a>
            </div>

            <!-- ‘Next’ button displayed if not on last page -->
            <div :if="$this->page < $this->totalPages">
                <a href="/blog?page={{ $this->page + 1 }}" class="px-4 py-2 bg-sky-500 text-white rounded">Next</a>
            </div>
        </div>
    </section>

</x-base>