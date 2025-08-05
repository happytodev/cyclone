<x-base>
    <h1>Login</h1>
    <?php if (isset($_GET['error']) ): ?>
        <div style="color: red;">An error occurred during authentication. Please try again.</div>
    <?php endif; ?>
    <div>
        <a href="/auth/github/redirect">Login with GitHub</a>
    </div>
    <!-- <div>
        <a href="/auth/google/redirect">Login with Google</a>
    </div>
    <div>
        <a href="/auth/twitter/redirect">Login with Twitter</a>
    </div> -->
</x-base>