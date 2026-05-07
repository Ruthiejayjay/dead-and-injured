<template>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div class="text-left">
                <p
                    class="text-xs font-black tracking-widest uppercase text-[#1a3a4a]/40"
                >
                    vs
                </p>
                <p class="font-black text-lg text-[#1a3a4a]">
                    {{ opponent.name }}
                </p>
            </div>
            <TimerDisplay :elapsed-ms="elapsedMs" />
        </div>

        <!-- Opponent progress -->
        <GameCard border-class="border-[#8b1a2f]/30">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-[#8b1a2f]"></div>
                    <span class="text-sm font-bold text-[#1a3a4a]">{{
                        opponent.name
                    }}</span>
                </div>
                <span
                    class="text-xs font-bold tracking-widest uppercase text-[#1a3a4a]/40"
                >
                    {{ opponentGuesses }}
                    {{ opponentGuesses === 1 ? "guess" : "guesses" }}
                </span>
            </div>
        </GameCard>

        <!-- Input -->
        <div class="flex gap-2">
            <input
                ref="inputRef"
                v-model="currentGuess"
                type="text"
                inputmode="numeric"
                maxlength="4"
                placeholder="Enter 4 digits..."
                @keyup.enter="submit"
                @input="sanitize"
                class="flex-1 py-4 px-5 rounded-xl border-2 border-[#1a3a4a]/20 bg-white text-[#1a3a4a] font-mono font-bold text-lg tracking-[0.3em] focus:outline-none focus:border-[#0d7a6b] transition-colors placeholder:tracking-normal placeholder:font-normal placeholder:text-[#1a3a4a]/30"
            />
            <button
                @click="submit"
                :disabled="currentGuess.length !== 4 || submitting"
                class="px-5 rounded-xl bg-[#1a3a4a] hover:bg-[#0d2535] disabled:opacity-30 disabled:cursor-not-allowed text-white transition-all active:scale-[0.97]"
            >
                <svg
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M13 7l5 5m0 0l-5 5m5-5H6"
                    />
                </svg>
            </button>
        </div>

        <p v-if="error" class="text-xs text-[#8b1a2f] font-semibold -mt-2 pl-1">
            {{ error }}
        </p>

        <!-- History -->
        <div
            class="bg-white/70 rounded-2xl border border-[#1a3a4a]/10 overflow-hidden"
        >
            <div
                class="flex items-center justify-between px-5 py-3 border-b border-[#1a3a4a]/10"
            >
                <span
                    class="text-xs font-black tracking-widest uppercase text-[#1a3a4a]"
                    >Your Guesses</span
                >
                <span class="text-xs text-[#1a3a4a]/40">
                    {{ history.length }}
                    {{ history.length === 1 ? "guess" : "guesses" }}
                </span>
            </div>
            <div class="divide-y divide-[#1a3a4a]/5">
                <div
                    v-if="history.length === 0"
                    class="flex flex-col items-center justify-center py-12 text-center"
                >
                    <p class="text-sm font-bold text-[#1a3a4a]/40">
                        No guesses yet!
                    </p>
                    <p class="text-xs text-[#1a3a4a]/30 mt-1">
                        Start cracking the code
                    </p>
                </div>
                <HistoryEntry
                    v-for="(entry, i) in history"
                    :key="i"
                    :guess="entry.guess"
                    :dead="entry.dead"
                    :injured="entry.injured"
                />
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import TimerDisplay from "@/Components/TimerDisplay.vue";
import GameCard from "@/Components/GameCard.vue";
import HistoryEntry from "@/Components/HistoryEntry.vue";

const props = defineProps({
    opponent: { type: Object, required: true },
    elapsedMs: { type: Number, required: true },
    opponentGuesses: { type: Number, default: 0 },
    history: { type: Array, default: () => [] },
    submitting: { type: Boolean, default: false },
});

const emit = defineEmits(["guess"]);

const currentGuess = ref("");
const error = ref("");
const inputRef = ref(null);

onMounted(() => inputRef.value?.focus());

function sanitize() {
    const seen = [];
    currentGuess.value = currentGuess.value
        .replace(/\D/g, "")
        .split("")
        .filter((d) => {
            if (seen.includes(d)) return false;
            seen.push(d);
            return true;
        })
        .join("")
        .slice(0, 4);
    error.value = "";
}

function submit() {
    if (currentGuess.value.length !== 4 || props.submitting) return;
    emit("guess", currentGuess.value);
    currentGuess.value = "";
}
</script>
