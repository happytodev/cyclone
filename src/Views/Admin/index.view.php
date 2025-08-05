<!-- <x-base-admin title="Blog Posts">
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Latest Posts</h1>-->

<!-- Post grid -->
<!-- <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <x-post :foreach="$this->posts as $post"> -->
<!-- <x-card /> -->
<!-- <div>
                    <div>{{ $post->title }}</div> 
                    <div><a href="/admin/edit/{{ $post->slug }}">Edit</a></div>
                </div>
            </x-post>
            <x-post :forelse>
                <p class="text-center text-gray-600 mt-8">It's quite empty here…</p>
            </x-post>
        </div>


</section>





</x-base-admin> -->

<!-- 2nd version -->
<!-- <x-base-admin title="Blog Posts">
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex flex-col sm:flex-row justify-between items-center mb-8 gap-4">
            <h1 class="text-3xl font-bold text-gray-900">Latest Posts</h1>
            <a href="/admin/posts/create" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition">Create New Post</a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <x-post :foreach="$this->posts as $post">
                <div class="bg-white shadow-md rounded-lg p-6 hover:shadow-lg transition">
                    <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ $post->title }}</h2>
                    <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $post->tldr }}</p>
                    <div class="text-sm text-gray-500 mb-4">
                        <span>{{ $post->published ? 'Publié' : 'Brouillon' }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <a href="/admin/edit/{{ $post->slug }}" class="text-blue-500 hover:text-blue-700 font-medium">Éditer</a>
                        <form action="/admin/posts/{{ $post->slug }}/delete" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer ce post ?');">
                            <button type="submit" class="text-red-500 hover:text-red-700 font-medium">Supprimer</button>
                        </form>
                    </div>
                </div>
            </x-post>
            <x-post :forelse>
                <p class="col-span-full text-center text-gray-600 mt-8">C’est assez vide ici…</p>
            </x-post>
        </div>
    </section>
</x-base-admin> -->

<!-- 3rd version -->
<x-base-admin title="Blog Posts">
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- En-tête avec bouton de création -->
        <div class="flex flex-col sm:flex-row justify-between items-center mb-8 gap-4">
            <h1 class="text-3xl font-bold text-gray-900">Latest Posts</h1>
            <a href="/admin/posts/create" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition">Create New Post</a>
        </div>

        <!-- Grille des posts -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <x-post :foreach="$this->posts as $post">
                <div class="bg-white shadow-md rounded-lg p-6 hover:shadow-lg transition">
                    <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ $post->title }}</h2>
                    <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $post->tldr }}</p>
                    <div class="text-sm text-gray-500 mb-4">
                        <span>{{ $post->published ? 'Publié' : 'Brouillon' }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <a href="/admin/edit/{{ $post->slug }}" class="text-blue-500 hover:text-blue-700 font-medium">Éditer</a>
                        <button @click="openModal('{{ $post->slug }}')" class="text-red-500 hover:text-red-700 font-medium">Supprimer</button>
                    </div>
                </div>
            </x-post>
            <x-post :forelse>
                <p class="col-span-full text-center text-gray-600 mt-8">C’est assez vide ici…</p>
            </x-post>
        </div>

        <!-- Modal de confirmation de suppression -->
        <div x-data="{ open: false, slug: '' }" x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-lg p-6 shadow-lg">
                <h3 class="text-lg font-bold mb-4">Confirmer la suppression</h3>
                <p class="mb-4">Êtes-vous sûr de vouloir supprimer ce post ? Cette action est irréversible.</p>
                <div class="flex justify-end gap-4">
                    <button @click="open = false" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-4 py-2 rounded">Annuler</button>
                    <form method="POST" class="delete-form">
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold px-4 py-2 rounded">Supprimer</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Script pour ouvrir le modal -->
    <script>
        function openModal(slug) {
            const modalData = document.querySelector('[x-data]').__x.$data;
            modalData.slug = slug;
            modalData.open = true;
            const form = document.querySelector('.delete-form');
            form.action = `/admin/posts/${slug}/delete`;
        }
    </script>
</x-base-admin>