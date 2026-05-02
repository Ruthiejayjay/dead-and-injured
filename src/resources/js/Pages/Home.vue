<template>
    <div
        class="min-h-screen flex flex-col items-center justify-center bg-[#dce9f0] px-4"
    >
        <HowToPlayModal v-if="showModal" @close="showModal = false" />

        <div class="flex flex-col items-center gap-8 w-full max-w-md">
            <!-- Logo -->
            <div class="flex flex-col items-center gap-4">
                <img
                    :src="logoImage"
                    alt="Dead & Injured"
                    class="w-36 h-36 object-contain"
                />
                <div class="text-center">
                    <h1
                        class="font-lora text-4xl font-black text-[#1a3a4a] uppercase"
                    >
                        Dead <span class="text-[#8b1a2f]">&</span> Injured
                    </h1>
                </div>
            </div>

            <!-- How to Play -->
            <button
                @click="showModal = true"
                class="flex items-center gap-2 text-sm text-[#1a3a4a]/60 hover:text-[#1a3a4a] transition-colors group"
            >
                <!-- <svg
                    class="w-4 h-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <circle cx="12" cy="12" r="10" stroke-width="1.5" />
                    <path
                        d="M12 16v-4M12 8h.01"
                        stroke-width="2"
                        stroke-linecap="round"
                    />
                </svg> -->
                <span class="font-montserrat underline underline-offset-2 decoration-dotted"
                    >How To Play</span
                >
            </button>

            <!-- Buttons -->
            <div class="flex flex-col gap-4 w-full">
                <Link
                    href="/play"
                    class="block w-full py-3.5 rounded-full text-white text-center font-bold tracking-[0.2em] uppercase text-sm border border-black bg-[linear-gradient(180deg,#005143_0%,#07B1B1_100%),linear-gradient(180deg,rgba(0,43,45,0.53)_0%,rgba(7,177,163,0)_100%)] shadow-[inset_0_2px_4px_rgba(255,255,255,0.2),0_4px_10px_rgba(0,0,0,0.25)] hover:brightness-110 active:scale-[0.98] transition-all"
                >
                    Play
                </Link>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";
import HowToPlayModal from "@/Components/HowToPlayModal.vue";
import logoImage from "/public/images/logo.png";

const showModal = ref(false);
</script>
