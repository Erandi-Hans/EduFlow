{{-- Delete / remove confirmation window with a blurred background.
     Include it once on a page:  @include('layouts.partials.confirm-modal')
     Then mark any form that needs a confirmation:
       <form method="POST" action="..." data-confirm-title="Remove Kasun?" data-confirm-text="..." data-confirm-button="Yes, remove"> --}}
<dialog id="confirm-dialog" class="m-auto w-full max-w-md rounded-3xl p-0 shadow-2xl backdrop:bg-slate-900/50 backdrop:backdrop-blur-sm">
    <div class="p-7">
        <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-red-50 text-red-600">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6" />
            </svg>
        </div>
        <h3 id="confirm-title" class="text-xl font-extrabold text-slate-900">Are you sure?</h3>
        <p id="confirm-text" class="mt-1 text-sm text-slate-500"></p>
        <div class="mt-6 flex justify-end gap-3">
            <button type="button" id="confirm-no"
                class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Cancel</button>
            <button type="button" id="confirm-yes"
                class="rounded-xl bg-[#c0392b] px-4 py-2.5 text-sm font-semibold text-white hover:bg-[#a93226]">Yes, delete</button>
        </div>
    </div>
</dialog>

<script>
    (function() {
        const dialog = document.getElementById('confirm-dialog');
        let pendingForm = null;

        // Intercept the submit of any form that has data-confirm-title
        document.addEventListener('submit', function(e) {
            const form = e.target;
            if (!form.matches('form[data-confirm-title]')) return;

            e.preventDefault();
            pendingForm = form;
            document.getElementById('confirm-title').textContent = form.dataset.confirmTitle;
            document.getElementById('confirm-text').textContent = form.dataset.confirmText || '';
            document.getElementById('confirm-yes').textContent = form.dataset.confirmButton || 'Yes, delete';
            dialog.showModal();
        });

        document.getElementById('confirm-yes').addEventListener('click', function() {
            if (pendingForm) pendingForm.submit(); // form.submit() does not trigger the submit event again
        });
        document.getElementById('confirm-no').addEventListener('click', () => dialog.close());
        dialog.addEventListener('click', (e) => {
            if (e.target === dialog) dialog.close();
        }); // click outside = cancel
    })();
</script>