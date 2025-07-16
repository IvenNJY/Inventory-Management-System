
<div class="text-center">
  <button type="button" class="hidden" aria-haspopup="dialog" aria-expanded="false" aria-controls="confirmationModal" id="openConfirmationModal">
    Open modal
  </button>
</div>

<div id="confirmationModal" class="confirmation-overlay hidden fixed inset-0 z-[9999] flex items-center justify-center transition-opacity duration-500 ease-out" style="opacity:0;" role="dialog" tabindex="-1" aria-labelledby="confirmationModalLabel">
  <div class="confirmation-modal opacity-0 w-full max-w-lg mx-auto transition-all duration-500 ease-out flex items-center justify-center" style="opacity:0;">
    <div id="confirmationModalCard" class="relative flex flex-col bg-white shadow-lg rounded-xl w-full">
      <div class="absolute top-2 end-2">
        <button type="button" class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-gray-100 text-gray-800 hover:bg-gray-200" aria-label="Close" id="closeConfirmationModal">
          <span class="sr-only">Close</span>
          <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
        </button>
      </div>
      <div class="p-4 sm:p-10 text-center overflow-y-auto">
        <span id="confirmationModalIcon" class=" mb-4 inline-flex justify-center items-center size-15.5 rounded-full border-4 border-yellow-50 bg-blue-100 text-blue-500" style="display: none;">
          <svg id="confirmationModalSvg" class="hidden shrink-0 size-7" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
            <!-- Modern info icon -->
            <g id="svgInfo" style="display: none;">
              <circle cx="12" cy="12" r="10" fill="currentColor" opacity="0.15"/>
              <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2" fill="none"/>
              <rect x="11" y="10" width="2" height="6" rx="1" fill="currentColor"/>
              <rect x="11" y="7" width="2" height="2" rx="1" fill="currentColor"/>
            </g>
            <!-- Modern warning icon -->
            <g id="svgWarning" style="display: none;">
              <circle cx="12" cy="12" r="10" fill="currentColor" opacity="0.15"/>
              <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2" fill="none"/>
              <rect x="11" y="7" width="2" height="7" rx="1" fill="currentColor"/>
              <circle cx="12" cy="16" r="1.5" fill="currentColor"/>
            </g>
          </svg>
        </span>
        <h3 id="confirmationModalLabel" class="mb-2 text-2xl font-bold text-gray-800">Confirmation</h3>
        <p class="text-gray-500" id="confirmationModalBody">Are you sure you want to proceed?</p>
        <div class="mt-6 flex flex-col gap-3 w-full">
          <div class="flex flex-row gap-3 w-full">
            <button type="button" id="confirmModalAction" class="w-full py-2 px-3 inline-flex items-center justify-center gap-x-2 text-md font-regular rounded-lg border bg-blue-600 text-white border-blue-600 hover:bg-blue-700">
              Confirm
            </button>
            <button type="button" id="cancelModalAction" class="w-full py-2 px-3 inline-flex items-center justify-center gap-x-2 text-md font-medium rounded-lg border border-gray-300 bg-white text-gray-800 hover:bg-gray-50">
              Cancel
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
.confirmation-overlay {
    background-color: color-mix(in oklab, var(--color-gray-900) /* oklch(21% 0.034 264.665) = #101828 */ 25%, transparent);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s ease-in-out;
}
.confirmation-overlay.show {
  opacity: 1 !important;
  pointer-events: auto;
}
.confirmation-overlay.hide {
  opacity: 0 !important;
  pointer-events: none;
}
.confirmation-modal {
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  transition: opacity 0.5s ease;
}
.confirmation-modal.show {
  opacity: 1 !important;
}
.confirmation-modal.hide {
  opacity: 0 !important;
}
</style>

<script>
let confirmationCallback = null;

function showConfirmationModal(title, body, onConfirm, type = 'confirmation') {
  const overlay = document.getElementById('confirmationModal');
  const modal = overlay.querySelector('.confirmation-modal');
  const card = document.getElementById('confirmationModalCard');
  const icon = document.getElementById('confirmationModalIcon');
  const svgInfo = document.getElementById('svgInfo');
  const svgWarning = document.getElementById('svgWarning');
  const confirmBtn = document.getElementById('confirmModalAction');
  const cancelBtn = document.getElementById('cancelModalAction');
  document.getElementById('confirmationModalLabel').textContent = title || 'Confirmation';
  document.getElementById('confirmationModalBody').textContent = body || 'Are you sure you want to proceed?';
  confirmationCallback = onConfirm;

  // Color and icon logic
  if (type === 'warning') {
    card.classList.remove('border-blue-600');
    card.classList.add('border-red-600');
    icon.classList.remove('bg-blue-100', 'text-blue-500');
    icon.classList.add('bg-red-100', 'text-red-500');
    svgInfo.style.display = 'none';
    svgWarning.style.display = 'block';
    confirmBtn.classList.remove('bg-blue-600', 'text-white', 'border-blue-600', 'hover:bg-blue-700');
    confirmBtn.classList.add('bg-red-600', 'text-white', 'border-red-600', 'hover:bg-red-700','focus:bg-red-700');
    cancelBtn.classList.remove('bg-gray-200', 'text-gray-800', 'hover:bg-gray-300', 'bg-blue-600', 'hover:bg-blue-700');
    cancelBtn.classList.add('bg-white', 'text-gray-800', 'border-gray-200', 'hover:bg-gray-50');
  } else {
    card.classList.remove('border-red-600');
    card.classList.add('border-blue-600');
    icon.classList.remove('bg-red-100', 'text-red-500');
    icon.classList.add('bg-blue-100', 'text-blue-500');
    svgInfo.style.display = 'block';
    svgWarning.style.display = 'none';
    confirmBtn.classList.remove('bg-red-600', 'text-white', 'border-red-600', 'hover:bg-red-700');
    confirmBtn.classList.add('bg-blue-600', 'text-white', 'border-blue-600', 'hover:bg-blue-700');
    cancelBtn.classList.remove('bg-gray-200', 'text-gray-800', 'hover:bg-gray-300', 'bg-blue-600', 'hover:bg-blue-700');
    cancelBtn.classList.add('bg-white', 'text-gray-800', 'border-gray-200', 'hover:bg-gray-50');
  }

  overlay.classList.remove('hidden', 'hide');
  overlay.classList.add('show');
  modal.classList.remove('hide');
  modal.classList.add('show');
  setTimeout(() => {
    overlay.style.opacity = 1;
    modal.style.opacity = 1;
  }, 10);
}

function hideConfirmationModal() {
  const overlay = document.getElementById('confirmationModal');
  const modal = overlay.querySelector('.confirmation-modal');
  overlay.classList.remove('show');
  overlay.classList.add('hide');
  modal.classList.remove('show');
  modal.classList.add('hide');
  setTimeout(() => {
    overlay.classList.add('hidden');
    overlay.style.opacity = 0;
    modal.style.opacity = 0;
  }, 500);
}

document.getElementById('openConfirmationModal').addEventListener('click', function() {
  showConfirmationModal('Sign out', 'Are you sure you would like to sign out?', function() {
    alert('Confirmed!'); // Replace with your action
    hideConfirmationModal();
  }, 'logout');
});

document.getElementById('closeConfirmationModal').addEventListener('click', hideConfirmationModal);
document.getElementById('cancelModalAction').addEventListener('click', hideConfirmationModal);
document.getElementById('confirmModalAction').addEventListener('click', function() {
  if (typeof confirmationCallback === 'function') {
    confirmationCallback();
  } else {
    hideConfirmationModal();
  }
});
</script>

<!-- 
document.getElementById('logoutButton').addEventListener('click', function(event) {
    event.preventDefault();
    showConfirmationModal(
        'Logout Confirmation',
        'Are you sure you want to logout?',
        function() {
            document.getElementById('logoutForm').submit();
        },
        'warning' // <-- This should be the fourth argument
    );
}); 
-->