<template>
    <div
        :class="[
            'bg-[#f0ede4] rounded-2xl border-2 p-8 space-y-4',
            borderClass
        ]"
    >
        <slot />
    </div>
</template>

<script setup>
defineProps({
    borderClass: { type: String, default: 'border-[#1a3a4a]' },
})
</script>