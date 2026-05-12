<template>
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
            <div
                :class="[
                    'w-2 h-2 rounded-full',
                    player.ready
                        ? 'bg-[#0d7a6b]'
                        : 'bg-[#1a3a4a]/20 animate-pulse',
                ]"
            ></div>
            <span class="text-sm font-bold text-[#1a3a4a]">{{
                player.name
            }}</span>
            <span v-if="isYou" class="text-xs text-[#1a3a4a]/40">(you)</span>
        </div>
        <span
            :class="[
                'text-xs font-bold tracking-widest uppercase',
                player.ready ? 'text-[#0d7a6b]' : 'text-[#1a3a4a]/40',
            ]"
        >
            {{ player.ready ? "Ready" : "Setting..." }}
        </span>
    </div>
</template>

<script setup>
defineProps({
    player: { type: Object, required: true },
    isYou: { type: Boolean, default: false },
});
</script>
