<template>
    <div
        class="absolute inset-0 bg-[#dce9f0]/80 backdrop-blur-sm rounded-2xl flex flex-col items-center justify-center z-10 gap-3"
    >
        <svg
            class="w-10 h-10 text-[#1a3a4a]/40"
            fill="currentColor"
            viewBox="0 0 24 24"
        >
            <rect x="5" y="4" width="4" height="16" rx="1" />
            <rect x="15" y="4" width="4" height="16" rx="1" />
        </svg>
        <p
            class="text-sm font-black tracking-widest uppercase text-[#1a3a4a]/40"
        >
            Paused
        </p>
        <button
            @click="$emit('resume')"
            class="mt-2 px-6 py-2.5 rounded-full bg-[#0d7a6b] hover:bg-[#0a6459] text-white font-bold tracking-widest uppercase text-xs transition-all active:scale-[0.98]"
        >
            Resume
        </button>
    </div>
</template>

<script setup>
defineEmits(["resume"]);
</script>
