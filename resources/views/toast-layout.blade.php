@if (session('status'))
    <div id="toast-status" style="position:fixed;bottom:1.25rem;left:50%;transform:translateX(-50%);z-index:9999;max-width:20rem;" role="alert" tabindex="-1" aria-labelledby="toast-status-label">
        <div id="toast-status-label" class="flex p-4 bg-green-100 border border-green-200 text-sm text-green-800 rounded-lg shadow-lg animate-fade-in-out">
            <span>{{ session('status') }}</span>
            <div class="ms-auto">
                <button type="button" onclick="document.getElementById('toast-status').style.display='none'" class="inline-flex shrink-0 justify-center items-center size-5 rounded-lg text-green-800 opacity-50 hover:opacity-100 focus:outline-hidden focus:opacity-100" aria-label="Close">
                    <span class="sr-only">Close</span>
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
@endif
@if ($errors->any())
    <div id="toast-error" style="position:fixed;bottom:2rem;left:50%;transform:translateX(-50%);z-index:9999;max-width:20rem;width:30%" role="alert" tabindex="-1" aria-labelledby="toast-error-label">
        <div id="toast-error-label" class="flex p-4 bg-red-100  border border-red-200 text-sm text-red-800 rounded-lg shadow-lg animate-fade-in-out items-start">
            <div class="flex-1">
                @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
            <div class="ms-auto pl-4">
                <button type="button" onclick="document.getElementById('toast-error').style.display='none'" class="inline-flex shrink-0 justify-center items-center size-5 rounded-lg text-red-800 opacity-50 hover:opacity-100 focus:outline-hidden focus:opacity-100" aria-label="Close">
                    <span class="sr-only">Close</span>
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
@endif
<style>
    @keyframes fade-in-out {
        0% { opacity: 0; transform: translateY(20px); }
        10% { opacity: 1; transform: translateY(0); }
        90% { opacity: 1; transform: translateY(0); }
        100% { opacity: 0; transform: translateY(20px); }
    }
    .animate-fade-in-out {
        animation: fade-in-out 3s ease-in;
    }
</style>
<script>
// Animate once, then hide the toast (auto-dismiss after animation)
function hideToastAfterAnimation(id) {
    const toast = document.getElementById(id);
    if (!toast) return;
    const box = toast.querySelector('div');
    box.addEventListener('animationend', function handler() {
        toast.style.display = 'none';
        box.removeEventListener('animationend', handler);
    });
}
hideToastAfterAnimation('toast-status');
hideToastAfterAnimation('toast-error');
</script>
