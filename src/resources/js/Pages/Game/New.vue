<template>
    <div
        class="min-h-screen flex flex-col items-center justify-center bg-[#dce9f0] px-4"
    >
        <div class="w-full max-w-md space-y-10">
            <!-- Back -->
            <Link
                href="/play"
                class="flex items-center gap-2 text-[#1a3a4a]/60 hover:text-[#1a3a4a] transition-colors text-sm w-fit"
            >
                <svg
                    class="w-4 h-4"
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
                    class="text-3xl font-black tracking-widest uppercase text-[#1a3a4a]"
                >
                    New Game
                </h2>
                <p class="text-sm text-[#1a3a4a]/50 mt-2">
                    Choose how you want to play
                </p>
            </div>

            <!-- vs Computer -->
            <div
                class="bg-[#f0ede4] rounded-2xl border-2 border-[#1a3a4a] p-8 space-y-4"
            >
                <div class="flex items-center gap-3 mb-2">
                    <div
                        class="w-10 h-10 rounded-full bg-[#0d7a6b] flex items-center justify-center flex-shrink-0"
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
                                d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                            />
                        </svg>
                    </div>
                    <h3
                        class="text-lg font-black tracking-wider uppercase text-[#1a3a4a]"
                    >
                        vs Computer
                    </h3>
                </div>
                <p class="text-sm text-[#1a3a4a]/60 leading-relaxed">
                    The computer picks a secret 4-digit code. Crack it in as few
                    guesses as possible.
                </p>
                <Link
                    href="/game/solo"
                    class="block w-full py-3.5 rounded-full bg-[#0d7a6b] hover:bg-[#0a6459] text-white text-center font-bold tracking-[0.2em] uppercase text-sm transition-all active:scale-[0.98] shadow-md shadow-[#0d7a6b]/30"
                >
                    Play
                </Link>
            </div>

            <!-- Multiplayer -->
            <div
                class="bg-[#f0ede4] rounded-2xl border-2 border-[#8b1a2f]/30 p-8 space-y-4 opacity-50"
            >
                <div class="flex items-center gap-3 mb-2">
                    <div
                        class="w-10 h-10 rounded-full bg-[#8b1a2f]/40 flex items-center justify-center flex-shrink-0"
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
                    <div>
                        <h3
                            class="text-lg font-black tracking-wider uppercase text-[#1a3a4a]"
                        >
                            Multiplayer
                        </h3>
                        <span
                            class="text-xs font-bold tracking-widest uppercase text-[#8b1a2f]/70"
                            >Coming soon</span
                        >
                    </div>
                </div>
                <p class="text-sm text-[#1a3a4a]/60 leading-relaxed">
                    Challenge a friend online. Set codes for each other and race
                    to crack them first.
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
</script>
