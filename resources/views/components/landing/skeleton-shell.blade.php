<div class="fixed inset-0 z-[9999] bg-white overflow-hidden" x-show="isLoading" x-transition:leave="transition ease-in duration-500" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    {{-- Skeleton Navbar --}}
    <div class="h-20 border-b border-zinc-100 px-6 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <x-skeleton type="circle" width="w-10" height="h-10" />
            <x-skeleton width="w-32" height="h-6" />
        </div>
        <div class="hidden lg:flex items-center gap-8">
            <x-skeleton width="w-16" height="h-4" />
            <x-skeleton width="w-16" height="h-4" />
            <x-skeleton width="w-16" height="h-4" />
            <x-skeleton width="w-16" height="h-4" />
        </div>
        <div class="flex items-center gap-4">
            <x-skeleton width="w-20" height="h-10" class="rounded-full" />
        </div>
    </div>

    {{-- Skeleton Content --}}
    <div class="max-w-7xl mx-auto px-6 pt-12">
        {{-- Hero --}}
        <div class="max-w-3xl mx-auto text-center space-y-6 mb-20">
            <div class="flex justify-center">
                <x-skeleton width="w-40" height="h-6" class="rounded-full" />
            </div>
            <x-skeleton width="w-full" height="h-16" />
            <x-skeleton width="w-3/4" height="h-16" class="mx-auto" />
            <x-skeleton width="w-1/2" height="h-6" class="mx-auto" />
            <div class="flex justify-center gap-4 pt-4">
                <x-skeleton width="w-40" height="h-12" class="rounded-full" />
                <x-skeleton width="w-40" height="h-12" class="rounded-full" />
            </div>
        </div>

        {{-- Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <x-skeleton height="h-80" class="md:col-span-2 rounded-3xl" />
            <x-skeleton height="h-80" class="rounded-3xl" />
            <x-skeleton height="h-60" class="rounded-3xl" />
            <x-skeleton height="h-60" class="md:col-span-2 rounded-3xl" />
        </div>
    </div>
</div>
