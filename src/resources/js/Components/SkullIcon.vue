<template>
    <svg :class="['w-5 h-5', colorClass]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3a7 7 0 00-7 7c0 2.5 1.5 4.5 3 5.5V18a1 1 0 001 1h6a1 1 0 001-1v-2.5c1.5-1 3-3 3-5.5a7 7 0 00-7-7z"/>
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 18h6M10 21h4"/>
        <path v-if="injured" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 7v3M10.5 8.5h3"/>
    </svg>
</template>

<script setup>
defineProps({
    injured: { type: Boolean, default: false },
    colorClass: { type: String, default: 'text-[#8b1a2f]' },
})
</script>