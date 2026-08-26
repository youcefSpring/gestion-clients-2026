<select {{ $attributes->merge(['class' => 'w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-[15px] text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200']) }}>
    {{ $slot }}
</select>
