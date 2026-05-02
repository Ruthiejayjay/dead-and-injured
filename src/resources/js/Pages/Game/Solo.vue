<template>
    <div
        class="min-h-screen flex flex-col items-center bg-[#dce9f0] px-3 sm:px-4 py-6 sm:py-8 md:py-10"
    >
        <div class="w-full max-w-md space-y-4 sm:space-y-6 relative">
            <!-- Header Controls -->
            <div class="flex items-center justify-between gap-2">
                <BackLink href="/play" />
                <TimerDisplay
                    :elapsed-ms="elapsedMs"
                    class="text-xs sm:text-sm"
                />
                <PauseButton
                    :paused="paused"
                    :disabled="won"
                    @click="togglePause"
                />
            </div>

            <!---Paused overlay-->
            <PauseOverlay v-if="paused && !won" @resume="resumeGame" />

            <!-- Input -->
            <div class="flex gap-2">
                <input
                    ref="inputRef"
                    v-model="currentGuess"
                    type="text"
                    inputmode="numeric"
                    maxlength="4"
                    placeholder="Enter 4 digits..."
                    :disabled="won || paused"
                    @keyup.enter="submitGuess"
                    @input="sanitizeInput"
                    class="flex-1 py-3 sm:py-4 px-4 sm:px-5 rounded-xl border-2 border-[#1a3a4a]/20 bg-white text-[#1a3a4a] font-mono font-bold text-base sm:text-lg tracking-[0.2em] sm:tracking-[0.3em] focus:outline-none focus:border-[#0d7a6b] transition-colors placeholder:tracking-normal placeholder:font-normal placeholder:text-[#1a3a4a]/30 disabled:opacity-50 placeholder:text-xs sm:placeholder:text-sm"
                />
                <button
                    @click="submitGuess"
                    :disabled="currentGuess.length !== 4 || won || paused"
                    class="px-4 sm:px-5 rounded-xl bg-[#1a3a4a] hover:bg-[#0d2535] disabled:opacity-30 disabled:cursor-not-allowed text-white transition-all active:scale-[0.97] flex-shrink-0"
                >
                    <svg
                        class="w-4 h-4 sm:w-5 sm:h-5"
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

            <p
                v-if="error"
                class="text-xs text-[#8b1a2f] font-semibold tracking-wide -mt-2 pl-1"
            >
                {{ error }}
            </p>

            <!-- History -->
            <div
                class="bg-white/70 rounded-2xl border border-[#1a3a4a]/10 overflow-hidden"
            >
                <div
                    class="flex items-center justify-between px-4 sm:px-5 py-2.5 sm:py-3 border-b border-[#1a3a4a]/10"
                >
                    <span
                        class="text-xs font-black tracking-widest uppercase text-[#1a3a4a]"
                        >History</span
                    >
                    <span class="text-xs text-[#1a3a4a]/40 font-medium">
                        {{ history.length }}
                        {{ history.length === 1 ? "guess" : "guesses" }}
                    </span>
                </div>
                <div class="divide-y divide-[#1a3a4a]/5">
                    <div
                        v-if="history.length === 0"
                        class="flex flex-col items-center justify-center py-12 sm:py-16 text-center"
                    >
                        <p class="text-sm font-bold text-[#1a3a4a]/40">
                            No guesses yet!
                        </p>
                        <p class="text-xs text-[#1a3a4a]/30 mt-1">
                            Start playing
                        </p>
                    </div>
                    <HistoryEntry
                        v-for="(entry, index) in history"
                        :key="index"
                        :guess="entry.guess"
                        :dead="entry.dead"
                        :injured="entry.injured"
                    />
                </div>
            </div>
        </div>

        <!-- Win Modal -->
        <Teleport to="body">
            <div
                v-if="won"
                class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-3 sm:p-4"
            >
                <GameCard class="w-full max-w-md space-y-5 sm:space-y-6">
                    <div class="text-center space-y-1">
                        <p class="text-3xl sm:text-4xl mb-2 sm:mb-3">🎉</p>
                        <h2
                            class="text-xl sm:text-2xl font-black tracking-widest uppercase text-[#1a3a4a]"
                        >
                            You cracked it!
                        </h2>
                        <p class="text-xs sm:text-sm text-[#1a3a4a]/50">
                            The code was
                            <span class="font-black text-[#1a3a4a]">{{
                                secretCode
                            }}</span>
                        </p>
                    </div>
                    <div class="grid grid-cols-2 gap-2 sm:gap-3">
                        <div
                            class="bg-white rounded-xl p-3 sm:p-4 text-center border border-[#1a3a4a]/10"
                        >
                            <p
                                class="text-xs font-bold tracking-widest uppercase text-[#1a3a4a]/40 mb-1"
                            >
                                Time
                            </p>
                            <TimerDisplay
                                :elapsed-ms="elapsedMs"
                                class="justify-center"
                            />
                        </div>
                        <div
                            class="bg-white rounded-xl p-3 sm:p-4 text-center border border-[#1a3a4a]/10"
                        >
                            <p
                                class="text-xs font-bold tracking-widest uppercase text-[#1a3a4a]/40 mb-1"
                            >
                                Guesses
                            </p>
                            <p
                                class="text-xl sm:text-2xl font-black font-mono text-[#1a3a4a]"
                            >
                                {{ history.length }}
                            </p>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2 sm:gap-3">
                        <PrimaryButton @click="resetGame"
                            >Play Again</PrimaryButton
                        >
                        <OutlineButton href="/">Home</OutlineButton>
                    </div>
                </GameCard>
            </div>
        </Teleport>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import BackLink from "@/Components/BackLink.vue";
import TimerDisplay from "@/Components/TimerDisplay.vue";
import GameCard from "@/Components/GameCard.vue";
import HistoryEntry from "@/Components/HistoryEntry.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import OutlineButton from "@/Components/OutlineButton.vue";
import PauseButton from "@/Components/PauseButton.vue";
import PauseOverlay from "@/Components/PauseOverlay.vue";

const secretCode = ref("");
const currentGuess = ref("");
const history = ref([]);
const error = ref("");
const won = ref(false);
const inputRef = ref(null);
const paused = ref(false);

const elapsedMs = ref(0);
let timerInterval = null;

function startTimer() {
    const start = Date.now() - elapsedMs.value;
    timerInterval = setInterval(() => {
        elapsedMs.value = Date.now() - start;
    }, 10);
}

function stopTimer() {
    clearInterval(timerInterval);
}

function generateCode() {
    const digits = [];
    while (digits.length < 4) {
        const d = Math.floor(Math.random() * 10).toString();
        if (!digits.includes(d)) digits.push(d);
    }
    return digits.join("");
}

function sanitizeInput() {
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

function evaluate(guess, secret) {
    let dead = 0;
    let injured = 0;
    for (let i = 0; i < 4; i++) {
        if (guess[i] === secret[i]) {
            dead++;
        } else if (secret.includes(guess[i])) {
            injured++;
        }
    }
    return { dead, injured };
}

function submitGuess() {
    if (won.value) return;
    if (currentGuess.value.length !== 4) {
        error.value = "Please enter exactly 4 digits.";
        return;
    }
    const guess = currentGuess.value;
    const result = evaluate(guess, secretCode.value);

    history.value.unshift({ guess, ...result });
    currentGuess.value = "";
    error.value = "";

    if (result.dead === 4) {
        stopTimer();
        won.value = true;
    }
}

function resetGame() {
    stopTimer();
    secretCode.value = generateCode();
    currentGuess.value = "";
    history.value = [];
    error.value = "";
    won.value = false;
    elapsedMs.value = 0;
    startTimer();
    inputRef.value?.focus();
}

function togglePause() {
    if (won.value) return;
    paused.value ? resumeGame() : pauseGame();
}

function pauseGame() {
    paused.value = true;
    stopTimer();
}

function resumeGame() {
    paused.value = false;
    startTimer();
    inputRef.value?.focus();
}

onMounted(() => {
    secretCode.value = generateCode();
    startTimer();
    inputRef.value?.focus();
});

onUnmounted(() => stopTimer());
</script>
