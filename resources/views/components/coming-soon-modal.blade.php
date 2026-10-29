{{-- Coming Soon Modal Component --}}
<div id="comingSoonModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 hidden">
    <div class="w-96 rounded-2xl bg-white p-6 shadow-xl text-center">

        {{-- Icon Container --}}
        <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-teal-50 text-[#00786F]">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>

        {{-- Heading and Message --}}
        <h3 class="text-lg font-bold text-slate-800">Coming Soon!</h3>
        <p class="mt-2 text-sm text-slate-600">This feature is currently under development and will be available soon.</p>

        {{-- Close Button --}}
        <button onclick="closeComingSoonModal()" class="mt-5 w-full rounded-xl bg-[#00786F] py-2 text-sm font-semibold text-white hover:bg-[#005f56]">
            OK
        </button>

    </div>
</div>

{{-- JavaScript Functions to Control the Modal --}}
<script>
    /**
     * Opens the Coming Soon modal and prevents default anchor navigation.
     * @param {Event} event 
     */
    function showComingSoonModal(event) {
        event.preventDefault();
        document.getElementById('comingSoonModal').classList.remove('hidden');
    }

    /**
     * Closes the Coming Soon modal by adding the hidden class.
     */
    function closeComingSoonModal() {
        document.getElementById('comingSoonModal').classList.add('hidden');
    }
</script>