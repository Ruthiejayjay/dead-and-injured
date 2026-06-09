<template>
    <div
        class="min-h-screen flex flex-col items-center justify-center bg-[#dce9f0] px-4"
    >
        <div class="w-full max-w-md space-y-8">
            <BlackLink href="/multiplayer" />
            <div class="text-center">
                <h2
                    class="text-3xl font-black tracking-widest uppercase text-[#1a3a4a]"
                >
                    Grand Prix
                </h2>
                <p class="text-sm text-[#1a3a4a]/50 mt-2">Mode Setup</p>
            </div>
            <GameCard>
                <h3
                    class="text-lg font-black tracking-wider uppercase text=[#1a3a4a] mb-6"
                >
                    Create a Tournament
                </h3>
                <div class="space-y-6">
                    <input
                        v-model="form.name"
                        type="text"
                        placeholder="Your name"
                        maxlength="20"
                        class="w-full py-3 px-4 rounded-xl border-2 border-[#1a3a4a]/20 bg-white text-[#1a3a4a] font-medium focus:outline-none focus:border-[#0d7a6b] transition-colors placeholder:text-[#1a3a4a]/30"
                    />
                    <p
                        v-if="form.errors.player_name"
                        class="text-xs text-[#8b1a2f] font-semibold pl-1"
                    >
                        {{ form.errors.player_name }}
                    </p>

                    <TournamentSlider
                        v-model="form.max_players"
                        label="Tournament Players"
                        :min="2"
                        :max="20"
                        :step="1"
                    />

                    <TournamentSlider
                        v-model="form.total_rounds"
                        label="Total Rounds"
                        :min="1"
                        :max="20"
                        :step="1"
                    />

                    <TournamentSlider
                        v-model="form.max_guesses"
                        label="Max Guesses per Round"
                        :min="5"
                        :max="20"
                        :step="1"
                    />

                    <TournamentSlider
                        v-model="form.time_limit"
                        label="Time Limit"
                        :min="30"
                        :max="300"
                        :step="10"
                        suffix="s"
                    />
                    <p
                        v-if="form.errors.max_players"
                        class="text-xs text-[#8b1a2f] font-semibold pl-1"
                    >
                        {{ form.errors.max_players }}
                    </p>
                    <p
                        v-if="form.errors.total_rounds"
                        class="text-xs text-[#8b1a2f] font-semibold pl-1"
                    >
                        {{ form.errors.total_rounds }}
                    </p>

                    <PrimaryButton
                        :disabled="form.processing || !form.name"
                        @click="createRoom"
                    >
                        {{
                            form.processing
                                ? "Creating..."
                                : "Create Tournament"
                        }}
                    </PrimaryButton>
                </div>
            </GameCard>
        </div>
    </div>
</template>

<script setup>
import { useForm } from "@inertiajs/vue3";
import BackLink from "@/Components/BackLink.vue";
import GameCard from "@/Components/GameCard.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TournamentSlider from "@/Components/GrandPrix/TournamentSlider.vue";

const form = useForm({
    player_name: "",
    name: "",
    max_players: 4,
    total_rounds: 5,
    max_guesses: 15,
    time_limit: 120,
});

function createRoom() {
    form.player_name = form.name;
    form.post(route("grand-prix.create"));
}
</script>
