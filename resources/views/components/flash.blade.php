@if (session('success'))
    <div
        x-data="{ show: true }"
        x-show="show"
        x-init="setTimeout(() => show = false, 5000)"
        x-transition
        class="mb-4 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 shadow-sm"
    >
        <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span class="flex-1">{{ session('success') }}</span>
        <button @click="show = false" class="text-emerald-500 hover:text-emerald-700">&times;</button>
    </div>
@endif

@if (session('error'))
    <div
        x-data="{ show: true }"
        x-show="show"
        x-init="setTimeout(() => show = false, 6000)"
        x-transition
        class="mb-4 flex items-start gap-3 rounded-xl border border-madorange-100 bg-madorange-50 px-4 py-3 text-sm text-madorange-700 shadow-sm"
    >
        <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-madorange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
        </svg>
        <span class="flex-1">{{ session('error') }}</span>
        <button @click="show = false" class="text-madorange-500 hover:text-madorange-700">&times;</button>
    </div>
@endif

@error('username')
    <div class="mb-4 rounded-xl border border-madorange-100 bg-madorange-50 px-4 py-3 text-sm text-madorange-700 shadow-sm">
        {{ $message }}
    </div>
@enderror
