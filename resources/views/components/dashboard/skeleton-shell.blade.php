<div class="fixed inset-0 z-[9999] bg-zinc-50 flex h-screen overflow-hidden animate-skeleton" 
     id="dashboard-skeleton-overlay">
    
    <!-- Sidebar Skeleton -->
    <aside class="hidden md:flex w-64 flex-col border-r border-zinc-200/80 bg-white/70 backdrop-blur-xl h-full shadow-sm">
        <div class="h-16 flex items-center px-6 border-b border-zinc-200/80">
            <x-skeleton type="rect" class="w-32 h-6 rounded-md opacity-20" />
        </div>
        <div class="flex-1 py-4 px-3 space-y-4">
            <x-skeleton type="rect" class="w-full h-10 rounded-xl opacity-10" />
            <x-skeleton type="rect" class="w-full h-10 rounded-xl opacity-10" />
            <x-skeleton type="rect" class="w-full h-10 rounded-xl opacity-10" />
            <div class="mt-4 pt-4 border-t border-zinc-200/60">
                <x-skeleton type="rect" class="w-3/4 h-8 rounded-lg opacity-10" />
            </div>
        </div>
    </aside>

    <!-- Main Content Skeleton -->
    <div class="flex-1 flex flex-col h-full">
        <!-- Header Skeleton -->
        <header class="h-16 flex items-center justify-between border-b border-zinc-200/80 bg-white/70 backdrop-blur-xl px-6">
            <x-skeleton type="rect" class="w-48 h-5 rounded opacity-10" />
            <div class="flex items-center gap-4">
                <div class="hidden sm:block text-right">
                    <x-skeleton type="rect" class="w-24 h-3 rounded mb-1 opacity-10 ml-auto" />
                    <x-skeleton type="rect" class="w-16 h-2 rounded opacity-10 ml-auto" />
                </div>
                <x-skeleton type="circle" class="w-9 h-9 opacity-10" />
            </div>
        </header>

        <!-- Content Area Skeleton -->
        <main class="flex-1 p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <x-skeleton type="rect" class="w-full h-32 rounded-3xl opacity-5" />
                <x-skeleton type="rect" class="w-full h-32 rounded-3xl opacity-5" />
                <x-skeleton type="rect" class="w-full h-32 rounded-3xl opacity-5" />
            </div>
            <x-skeleton type="rect" class="w-full h-64 rounded-3xl opacity-5" />
        </main>
    </div>
</div>
