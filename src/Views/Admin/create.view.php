<x-base-admin title="Create blog post">
    <h1 class="text-3xl font-bold mb-4">Créer un article</h1>
    <form method="POST" action="/admin/posts" id="postForm">
        <x-csrf-token />
        <input type="hidden" name="slug" value="">
        <!-- <input type="hidden" name="cover_image" value="{{ $post->cover_image }}"> -->
        <input type="hidden" name="markdown_file_path" value="path">
        <input type="hidden" name="cover_image" value="">
        <input type="hidden" name="published" value="true">
        <input type="text" name="title" placeholder="Titre" value="">
        <label for="tldr">TLDR</label>
        <input type="text" name="tldr" placeholder="TLDR" value="">
        <textarea name="markdown" id="markdownInput" style="display:none;"></textarea>
        <div id="editor" data-markdown="{{ $markdown ?? '' }}" class="mt-1 my-4 block w-full rounded-md border-gray-300 shadow-sm"></div>
        <div class="flex justify-center">
            <button type="submit" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">Save</button>
        </div>
    </form>
</x-base-admin>