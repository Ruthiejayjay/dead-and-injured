<template>
    <div
        class="bg-[#f0ede4] rounded-2xl border-2 border-[#8b1a2f] p-5 sm:p-6 md:p-8 space-y-3 sm:space-y-4"
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
                class="text-base sm:text-lg font-black tracking-wider uppercase text-[#1a3a4a]"
            >
                Join a Game
            </h3>
        </div>

        <p class="text-xs sm:text-sm text-[#1a3a4a]/60 leading-relaxed">
            Have a room code? Enter it and your name to jump straight in.
        </p>

        <input
            v-model="form.name"
            type="text"
            placeholder="Your name"
            maxlength="20"
            class="w-full py-3 px-4 rounded-xl border-2 border-[#1a3a4a]/20 bg-white text-[#1a3a4a] font-medium focus:outline-none focus:border-[#8b1a2f] transition-colors placeholder:text-[#1a3a4a]/30"
        />

        <input
            v-model="form.code"
            type="text"
            maxlength="4"
            placeholder="Room code"
            @input="form.code = form.code.replace(/\D/g, '')"
            class="w-full py-3 px-4 rounded-xl border-2 border-[#1a3a4a]/20 bg-white text-[#1a3a4a] font-mono font-bold text-center tracking-[0.4em] focus:outline-none focus:border-[#8b1a2f] transition-colors placeholder:tracking-normal placeholder:font-normal placeholder:text-[#1a3a4a]/30"
        />

        <p
            v-if="form.errors.code"
            class="text-xs text-[#8b1a2f] font-semibold pl-1"
        >
            {{ form.errors.code }}
        </p>
        <p
            v-if="form.errors.player_name"
            class="text-xs text-[#8b1a2f] font-semibold pl-1"
        >
            {{ form.errors.player_name }}
        </p>

        <button
            @click="join"
            :disabled="form.processing || !form.name || form.code.length !== 4"
            class="w-full py-3.5 rounded-full bg-[#8b1a2f] hover:bg-[#6e1424] disabled:opacity-40 disabled:cursor-not-allowed text-white font-bold tracking-[0.2em] uppercase text-sm transition-all active:scale-[0.98]"
        >
            {{ form.processing ? "Joining..." : "Join" }}
        </button>
    </div>
</template>

<script setup>
import { useForm } from "@inertiajs/vue3";

const form = useForm({ player_name: "", name: "", code: "" });

function join() {
    form.player_name = form.name;
    form.post(route("duel.join"));
}
</script>
