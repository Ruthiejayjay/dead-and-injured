<template>
    <div class="space-y-6">
        <!--  Round Header  -->
        <div class="flex items-center justify-between">
            <div>
                <p
                    class="text-xs font-black tracking-widest uppercase text-[#1a3a4a]/40"
                >
                    Round
                </p>
                <p class="font-black text-2xl text-[#1a3a4a]">
                    {{ currentRound
                    }}<span class="text-sm text-[#1a3a4a]/40 font-bold"
                        >/{{ totalRounds }}</span
                    >
                </p>
            </div>
            <RoundTimer
                :time-limit="timeLimit"
                :started-at="startedAt"
                @expired="$emit('time-expired')"
            />
        </div>
        <!--  Solved banner  -->
        <div
            v-if="solved"
            class="bg-[#0d7a6b]/10 border-2 border-[#0d7a6b] rounded-2xl p-6 text-center space-y-1"
        >
            <p class="text-2xl">🎯</p>
            <p class="font-black tracking-widest uppercase text-[#0d7a6b]">
                Code Cracked!
            </p>
            <p class="text-sm text-[#1a3a4a]/50">
                +{{ roundScore }} points — waiting for round to end...
            </p>
        </div>
        <!--  Max guesses banner  -->
        <div
            v-else-if="maxGuessesReached"
            class="bg-[#8b1a2f]/10 border-2 border-[#8b1a2f] rounded-2xl p-6 text-center space-y-1"
        >
            <p class="text-2xl">💀</p>
            <p class="font-black tracking-widest uppercase text-[#8b1a2f]">
                No More Guesses
            </p>
            <p class="text-sm text-[#1a3a4a]/50">Waiting for round to end...</p>
        </div>

        <!--  Input  -->
        <div v-else class="flex gap-2">
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

        <p v-if="error" class="text-[#8b1a2f] font-semibold -mt-2 pl-1">
            {{ error }}
        </p>

        <!-- Guesses remaining -->
        <div
            v-if="!solved && !maxGuessesReached"
            class="flex items-center justify-between text-xs text-[#1a3a4a]/40 font-bold tracking-widest uppercase px-1"
        >
            <span>Guesses</span>
            <span>{{ guessesUsed }} / {{ maxGuesses }}</span>
        </div>

        <!--  History  -->
        <div
            class="bg-white/70 rounded-2xl border border-[#1a3a4a]/10 overflow-hidden"
        >
            <div
                class="flex items-center justify-between px-5 py-3 border-b border-[#1a3a4a]/10"
            >
                <span
                    class="text-xs font-black tracking-widest uppercase text-[#1a3a4a]"
                    >History</span
                >
                <span class="text-xs text-[#1a3a4a]/40"
                    >{{ history.length }}
                    {{ history.length === 1 ? "guess" : "guesses" }}</span
                >
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
import HistoryEntry from "@/components/HistoryEntry.vue";
import RoundTimer from "@/components/GrandPrix/RoundTimer.vue";

const props = defineProps({
    currentRound: { type: Number, required: true },
    totalRounds: { type: Number, required: true },
    timeLimit: { type: Number, required: true },
    startedAt: { type: String, required: true },
    solved: { type: Boolean, default: false },
    roundScore: { type: Number, default: 0 },
    maxGuessesReached: { type: Boolean, default: false },
    guessesUsed: { type: Number, default: 0 },
    maxGuesses: { type: Number, required: true },
    history: { type: Array, default: () => [] },
    submitting: { type: Boolean, default: false },
});

const emit = defineEmits(["guess", "time-expired"]);
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
    if (
        currentGuess.value.length !== 4 ||
        props.submitting ||
        props.solved ||
        props.maxGuessesReached
    )
        return;
    emit("guess", currentGuess.value);
    currentGuess.value = "";
}
</script>
