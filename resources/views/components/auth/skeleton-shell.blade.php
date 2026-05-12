<div class="fixed inset-0 z-[9999] bg-white flex items-center justify-center p-6 animate-skeleton" 
     id="auth-skeleton-overlay">
    <div class="w-full max-w-md space-y-8">
        <div class="text-center space-y-4">
            <x-skeleton type="rect" class="w-48 h-10 rounded-xl mx-auto opacity-20" />
            <x-skeleton type="rect" class="w-64 h-5 rounded-lg mx-auto opacity-10" />
        </div>
        
        <div class="space-y-6">
            <div class="space-y-2">
                <x-skeleton type="rect" class="w-24 h-4 rounded opacity-10" />
                <x-skeleton type="rect" class="w-full h-14 rounded-2xl opacity-10" />
            </div>
            <div class="space-y-2">
                <x-skeleton type="rect" class="w-24 h-4 rounded opacity-10" />
                <x-skeleton type="rect" class="w-full h-14 rounded-2xl opacity-10" />
            </div>
            <x-skeleton type="rect" class="w-full h-14 rounded-2xl opacity-20 mt-8" />
        </div>

        <div class="relative my-8">
            <x-skeleton type="rect" class="w-full h-px opacity-10" />
        </div>

        <x-skeleton type="rect" class="w-full h-12 rounded-2xl opacity-10" />
    </div>
</div>
