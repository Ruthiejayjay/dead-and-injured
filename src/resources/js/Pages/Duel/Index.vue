<template>
    <div
        class="min-h-screen flex flex-col items-center justify-center bg-[#dce9f0] px-4"
    >
        <div class="w-full max-w-md space-y-8">
            <BackLink href="/multiplayer" />
            <div class="text-center">
                <h2
                    class="text-3xl font-black tracking-widest uppercase text-[#1a3a4a]"
                >
                    Duel Mode
                </h2>
                <p class="text-sm text-[#1a3a4a]/50 mt-2">
                    Head-to-head code breaking
                </p>
            </div>
            <!-- Create Room -->
            <GameCard>
                <h3
                    class="text-lg font-black tracking-wider uppercase text-[#1a3a4a] mb-4"
                >
                    Create a Room
                </h3>
                <div class="space-y-3">
                    <input
                        v-model="createForm.name"
                        type="text"
                        placeholder="Your name"
                        maxlength="20"
                        class="w-full py-3 px-4 rounded-xl border-2 border-[#1a3a4a]/20 bg-white text-[#1a3a4a] font-medium focus:outline-none focus:border-[#0d7a6b] transition-colors placeholder:text-[#1a3a4a]/30"
                    />
                    <p
                        v-if="createForm.errors.player_name"
                        class="text-xs text-[#8b1a2f] font-semibold pl-1"
                    >
                        {{ createForm.errors.player_name }}
                    </p>
                    <PrimaryButton
                        :disabled="createForm.processing || !createForm.name"
                        @click="createRoom"
                    >
                        {{
                            createForm.processing
                                ? "Creating..."
                                : "Create Room"
                        }}
                    </PrimaryButton>
                </div>
            </GameCard>

            <!-- Join Room -->
            <!-- <GameCard>
                <h3
                    class="text-lg font-black tracking-wider uppercase text-[#1a3a4a] mb-4"
                >
                    Join a Room
                </h3>
                <div class="space-y-3">
                    <input
                        v-model="joinForm.name"
                        type="text"
                        placeholder="Your name"
                        maxlength="20"
                        class="w-full py-3 px-4 rounded-xl border-2 border-[#1a3a4a]/20 bg-white text-[#1a3a4a] font-medium focus:outline-none focus:border-[#0d7a6b] transition-colors placeholder:text-[#1a3a4a]/30"
                    />
                    <input
                        v-model="joinForm.code"
                        type="text"
                        placeholder="Room code"
                        maxlength="6"
                        @input="
                            joinForm.code = joinForm.code.replace(/\D/g, '')
                        "
                        class="w-full py-3 px-4 rounded-xl border-2 border-[#1a3a4a]/20 bg-white text-[#1a3a4a] font-mono font-bold text-center tracking-[0.4em] focus:outline-none focus:border-[#0d7a6b] transition-colors placeholder:tracking-normal placeholder:font-normal placeholder:text-[#1a3a4a]/30"
                    />
                    <p
                        v-if="joinForm.errors.code"
                        class="text-xs text-[#8b1a2f] font-semibold pl-1"
                    >
                        {{ joinForm.errors.code }}
                    </p>
                    <p
                        v-if="joinForm.errors.player_name"
                        class="text-xs text-[#8b1a2f] font-semibold pl-1"
                    >
                        {{ joinForm.errors.player_name }}
                    </p>
                    <PrimaryButton
                        :disabled="
                            joinForm.processing ||
                            !joinForm.name ||
                            joinForm.code.length !== 6
                        "
                        @click="joinRoom"
                    >
                        {{ joinForm.processing ? "Joining..." : "Join Room" }}
                    </PrimaryButton>
                </div>
            </GameCard> -->
        </div>
    </div>
</template>

<script setup>
import { useForm } from "@inertiajs/vue3";
import BackLink from "@/Components/BackLink.vue";
import GameCard from "@/Components/GameCard.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const createForm = useForm({ player_name: "", name: "" });
const joinForm = useForm({ player_name: "", name: "", code: "" });

function createRoom() {
    createForm.player_name = createForm.name;
    createForm.post(route("duel.create"));
}

function joinRoom() {
    joinForm.player_name = joinForm.name;
    joinForm.post(route("duel.join"));
}
</script>
