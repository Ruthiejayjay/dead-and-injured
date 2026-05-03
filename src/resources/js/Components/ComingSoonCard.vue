<template>
    <div
        class="bg-[#f0ede4] rounded-2xl border-4 border-[#8b1a2f]/30 p-8 space-y-4 opacity-50"
    >
        <div class="flex items-center gap-3 mb-2">
            <div
                class="w-10 h-10 rounded-full bg-[#8b1a2f]/40 flex items-center justify-center flex-shrink-0"
            >
                <slot name="icon" />
            </div>
            <div>
                <h3
                    class="text-lg font-black tracking-wider uppercase text-[#1a3a4a]"
                >
                    <slot name="title" />
                </h3>
                <span
                    class="text-xs font-bold tracking-widest uppercase text-[#8b1a2f]/70"
                    >Coming soon</span
                >
            </div>
        </div>
        <p class="text-sm text-[#1a3a4a]/60 leading-relaxed">
            <slot name="description" />
        </p>
    </div>
</template>
