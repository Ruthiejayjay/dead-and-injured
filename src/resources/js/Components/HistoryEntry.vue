<template>
    <div class="flex items-center justify-between px-5 py-4">
        <span class="font-mono font-black text-xl tracking-[0.3em] text-[#1a3a4a]">{{ guess }}</span>
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-1.5">
                <SkullIcon />
                <span class="font-black text-sm text-[#8b1a2f]">{{ dead }}</span>
            </div>
            <div class="flex items-center gap-1.5">
                <SkullIcon injured color-class="text-[#0d7a6b]" />
                <span class="font-black text-sm text-[#0d7a6b]">{{ injured }}</span>
            </div>
        </div>
    </div>
</template>

<script setup>
import SkullIcon from '@/Components/SkullIcon.vue';

defineProps({
    guess: { type: String, required: true },
    dead: { type: Number, required: true },
    injured: { type: Number, required: true },
})
</script>