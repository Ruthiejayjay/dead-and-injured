<template>
    <button
        @click="$emit('click')"
        :disabled="disabled"
        class="flex items-center justify-center w-9 h-9 rounded-full bg-white/70 border border-[#1a3a4a]/10 hover:bg-white transition-colors disabled:opacity-30 disabled:cursor-not-allowed"
        :title="paused ? 'Resume' : 'Pause'"
    >
        <svg
            v-if="!paused"
            class="w-4 h-4 text-[#1a3a4a]"
            fill="currentColor"
            viewBox="0 0 24 24"
        >
            <rect x="5" y="4" width="4" height="16" rx="1" />
            <rect x="15" y="4" width="4" height="16" rx="1" />
        </svg>
        <svg
            v-else
            class="w-4 h-4 text-[#0d7a6b]"
            fill="currentColor"
            viewBox="0 0 24 24"
        >
            <path d="M6 4l14 8-14 8V4z" />
        </svg>
    </button>
</template>

<script setup>
defineProps({
    paused: { type: Boolean, default: false },
    disabled: { type: Boolean, default: false },
});

defineEmits(["click"]);
</script>
