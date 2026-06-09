<template>
    <div :class="['flex items-center gap-2 rounded-full px-4 py-2 border transition-colors', timerClass]">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10" stroke-width="1.5"/>
            <path stroke-linecap="round" stroke-width="1.5" d="M12 6v6l4 2"/>
        </svg>
        <span class="font-mono font-bold text-sm tracking-wider">{{ display }}</span>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'

const props = defineProps({
    timeLimit: { type: Number, required: true },
    startedAt: { type: String, required: true },
})

const emit = defineEmits(['expired'])

const secondsLeft = ref(props.timeLimit)
let interval = null

const display = computed(() => {
    const m = Math.floor(secondsLeft.value / 60)
    const s = secondsLeft.value % 60
    return `${m}:${String(s).padStart(2, '0')}`
})

const timerClass = computed(() => {
    if (secondsLeft.value <= 10) return 'bg-[#8b1a2f]/10 border-[#8b1a2f]/30 text-[#8b1a2f]'
    if (secondsLeft.value <= 30) return 'bg-yellow-50 border-yellow-200 text-yellow-700'
    return 'bg-white/70 border-[#1a3a4a]/10 text-[#1a3a4a]'
})

onMounted(() => {
    const started = new Date(props.startedAt).getTime()
    interval = setInterval(() => {
        const elapsed = Math.floor((Date.now() - started) / 1000)
        secondsLeft.value = Math.max(0, props.timeLimit - elapsed)
        if (secondsLeft.value === 0) {
            clearInterval(interval)
            emit('expired')
        }
    }, 1000)
})

onUnmounted(() => clearInterval(interval))
</script>