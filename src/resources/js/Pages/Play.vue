<template>
    <div
        class="min-h-screen flex flex-col items-center justify-center bg-[#dce9f0] px-4 py-8 sm:py-12 md:py-16"
    >
        <div class="w-full max-w-md space-y-6 sm:space-y-8 md:space-y-10">
            <!-- Back -->
            <Link
                href="/"
                class="inline-flex items-center gap-2 text-[#1a3a4a]/60 hover:text-[#1a3a4a] transition-colors text-sm sm:text-base w-fit"
            >
                <svg
                    class="w-4 h-4 flex-shrink-0"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 19l-7-7 7-7"
                    />
                </svg>
                Back
            </Link>

            <div class="text-center">
                <h2
                    class="font-hanuman text-2xl sm:text-3xl font-black tracking-widest uppercase text-[#1a3a4a]"
                >
                    Choose Mode
                </h2>
            </div>

            <!-- New Game -->
            <div
                class="bg-[#f0ede4] rounded-2xl border-2 border-[#1a3a4a] p-5 sm:p-6 md:p-8 space-y-3 sm:space-y-4"
            >
                <div class="flex items-center gap-3 mb-2">
                    <div
                        class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-[#0d7a6b] flex items-center justify-center flex-shrink-0"
                    >
                        <svg
                            class="w-4 h-4 sm:w-5 sm:h-5 text-white"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 4v16m8-8H4"
                            />
                        </svg>
                    </div>
                    <h3
                        class="font-hanuman text-base sm:text-lg font-black tracking-wider uppercase text-[#1a3a4a]"
                    >
                        New Game
                    </h3>
                </div>
                <p
                    class="font-montserrat text-xs sm:text-sm text-[#1a3a4a]/60 leading-relaxed"
                >
                    Start a fresh solo game. A secret 4-digit code will be
                    generated for you to crack.
                </p>
                <PrimaryButton
                    href="/game/solo"                >
                    Start
                </PrimaryButton>
            </div>

            <!-- Multiplayer -->
            <GameCard border-class="border-[#8b1a2f]">
                <div class="flex items-center gap-3 mb-2">
                    <div
                        class="w-10 h-10 rounded-full bg-[#8b1a2f] flex items-center justify-center flex-shrink-0"
                    >
                        <svg
                            class="w-5 h-5 text-white"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"
                            />
                        </svg>
                    </div>
                    <h3
                        class="text-lg font-black tracking-wider uppercase text-[#1a3a4a]"
                    >
                        Multiplayer
                    </h3>
                </div>
                <p class="text-sm text-[#1a3a4a]/60 leading-relaxed">
                    Challenge a friend online. Pick from Duel, Race, or Grand
                    Prix modes.
                </p>
                <PrimaryButton
                    href="/multiplayer" variant="purple"
                >
                    Play
                </PrimaryButton>
            </GameCard>

            <!-- Join Game -->
            <div
                class="bg-[#f0ede4] rounded-2xl border-2 border-[#8b1a2f] p-5 sm:p-6 md:p-8 space-y-3 sm:space-y-4 opacity-50"
            >
                <div class="flex items-center gap-3 mb-2">
                    <div
                        class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-[#8b1a2f] flex items-center justify-center flex-shrink-0"
                    >
                        <svg
                            class="w-4 h-4 sm:w-5 sm:h-5 text-white"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"
                            />
                        </svg>
                    </div>
                    <h3
                        class="font-hanuman text-base sm:text-lg font-black tracking-wider uppercase text-[#1a3a4a]"
                    >
                        Join a Game
                    </h3>
                </div>
                <p class="text-xs sm:text-sm text-[#1a3a4a]/60 leading-relaxed">
                    Have a game code? Enter it below to join a multiplayer
                    session.
                </p>
                <div class="flex flex-col sm:flex-row gap-2">
                    <input
                        v-model="gameCode"
                        type="text"
                        maxlength="6"
                        placeholder="Enter code"
                        class="flex-1 py-2.5 sm:py-3 px-4 rounded-full border-2 border-[#1a3a4a]/30 bg-white text-center text-[#1a3a4a] font-bold tracking-[0.2em] sm:tracking-[0.3em] uppercase text-sm focus:outline-none focus:border-[#8b1a2f] transition-colors placeholder:tracking-normal placeholder:font-normal placeholder:text-[#1a3a4a]/30"
                        @keyup.enter="joinGame"
                    />
                    <button
                        @click="joinGame"
                        :disabled="!gameCode"
                        class="w-full sm:w-auto py-2.5 sm:py-3 px-6 rounded-full bg-[#8b1a2f] hover:bg-[#6e1424] disabled:opacity-40 disabled:cursor-not-allowed text-white font-bold tracking-widest uppercase text-xs sm:text-sm transition-all active:scale-[0.98]"
                    >
                        Join
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { Link, router } from "@inertiajs/vue3";
import GameCard from "../Components/GameCard.vue";
import PrimaryButton from "../Components/PrimaryButton.vue";

const gameCode = ref("");

function joinGame() {
    if (!gameCode.value) return;
    router.visit(`/game/join/${gameCode.value}`);
}
</script>
