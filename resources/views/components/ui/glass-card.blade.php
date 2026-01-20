<div {!! $attributes->merge(['class' => 'relative overflow-hidden rounded-2xl border-2 border-zinc-300/80 bg-white/80 p-8 shadow-2xl shadow-zinc-200/50 backdrop-blur-xl']) !!}>
    <div class="absolute -top-24 -left-24 h-64 w-64 rounded-full bg-indigo-500/5 blur-3xl"></div>
    
    <div class="relative z-10">
        {{ $slot }}
    </div>
</div>