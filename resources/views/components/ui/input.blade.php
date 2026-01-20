@props(['disabled' => false])

<div class="relative group">
    <input 
        {{ $disabled ? 'disabled' : '' }}
        {!! $attributes->merge([
            'class' => 'flex h-12 w-full rounded-lg border-2 border-zinc-300 bg-white px-4 py-3 text-sm text-zinc-900 placeholder:text-zinc-400 focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all duration-300 shadow-sm'
        ]) !!}
    >
</div>