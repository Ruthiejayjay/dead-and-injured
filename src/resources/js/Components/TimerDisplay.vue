<template>
    <div
        class="flex items-center gap-2 bg-white/70 rounded-full px-4 py-2 border border-[#1a3a4a]/10"
    >
        <svg
            class="w-4 h-4 text-[#1a3a4a]/50"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
        >
            <circle cx="12" cy="12" r="10" stroke-width="1.5" />
            <path stroke-linecap="round" stroke-width="1.5" d="M12 6v6l4 2" />
        </svg>
        <span
            class="font-mono font-bold text-[#1a3a4a] text-sm tracking-wider"
            >{{ formatted }}</span
        >
    </div>
</template>

<script setup>
import { computed } from "vue";

const props = defineProps({
    elapsedMs: { type: Number, required: true },
});

const formatted = computed(() => {
    const totalSeconds = Math.floor(props.elapsedMs / 1000);
    const ms = Math.floor((props.elapsedMs % 1000) / 10);
    return `${totalSeconds}.${String(ms).padStart(2, "0")}s`;
});
</script>
