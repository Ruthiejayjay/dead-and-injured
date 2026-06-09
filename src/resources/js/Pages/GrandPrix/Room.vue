<template>
    <div
        class="min-h-screen flex flex-col items-center justify-center bg-[#dce9f0] px-4 py-10"
    >
        <div class="w-full max-w-md">
            <div class="mb-6">
                <BackLink href="/play" />
            </div>

            <!-- Waiting for Players -->
            <div v-if="room.status === 'waiting'" class="space-y-6">
                <div class="text-center space-y-2">
                    <h2
                        class="text-3xl font-black tracking-widest uppercase text-[#1a3a4a]"
                    >
                        Lobby
                    </h2>
                    <p class="text-sm text-[#1a3a4a]/50">
                        Waiting for players to join
                    </p>
                </div>

                <GameCard>
                    <p
                        class="text-xs font-black tracking-widest uppercase text-[#1a3a4a]/40 mb-2 text-center"
                    >
                        Room Code
                    </p>
                    <div class="flex items-center justify-center gap-3 mb-6">
                        <span
                            class="font-mono font-black text-4xl tracking-[0.4em] text-[#1a3a4a]"
                            >{{ room.code }}</span
                        >
                        <button
                            @click="copyCode"
                            class="p-2 rounded-lg bg-[#1a3a4a]/10 hover:bg-[#1a3a4a]/20 transition-colors"
                        >
                            <svg
                                v-if="!copied"
                                class="w-4 h-4 text-[#1a3a4a]"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"
                                />
                            </svg>
                            <svg
                                v-else
                                class="w-4 h-4 text-[#0d7a6b]"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5 13l4 4L19 7"
                                />
                            </svg>
                        </button>
                    </div>

                    <p
                        class="text-xs font-black tracking-widest uppercase text-[#1a3a4a]/40 mb-3"
                    >
                        Players ({{ players.length }}/{{ room.max_players }})
                    </p>
                    <div class="space-y-2 mb-6">
                        <div
                            v-for="p in players"
                            :key="p.id"
                            class="flex items-center gap-2"
                        >
                            <div
                                class="w-2 h-2 rounded-full bg-[#0d7a6b]"
                            ></div>
                            <span class="font-bold text-[#1a3a4a] text-sm">{{
                                p.name
                            }}</span>
                            <span
                                v-if="p.id === player.id"
                                class="text-xs text-[#1a3a4a]/40"
                                >(you)</span
                            >
                            <span
                                v-if="p.is_host"
                                class="text-xs font-bold tracking-widest uppercase text-[#0d7a6b]"
                                >Host</span
                            >
                        </div>
                    </div>

                    <div
                        class="bg-[#1a3a4a]/5 rounded-xl p-3 text-xs text-[#1a3a4a]/50 space-y-1 mb-6"
                    >
                        <p>
                            <span class="font-bold">Rounds:</span>
                            {{ room.total_rounds }}
                        </p>
                        <p>
                            <span class="font-bold">Max Guesses:</span>
                            {{ room.max_guesses }} per round
                        </p>
                        <p>
                            <span class="font-bold">Time Limit:</span>
                            {{ room.time_limit }}s per round
                        </p>
                    </div>

                    <PrimaryButton
                        v-if="player.is_host"
                        :disabled="players.length < 2 || starting"
                        @click="startTournament"
                    >
                        {{ starting ? "Starting..." : "Start Tournament" }}
                    </PrimaryButton>
                    <div
                        v-else
                        class="flex items-center justify-center gap-2 py-2"
                    >
                        <div
                            class="w-4 h-4 rounded-full border-2 border-[#0d7a6b]/40 border-t-[#0d7a6b] animate-spin"
                        ></div>
                        <p class="text-sm text-[#1a3a4a]/50">
                            Waiting for host to start...
                        </p>
                    </div>
                </GameCard>
            </div>

            <!-- Playing -->
            <RoundPlaying
                v-else-if="room.status === 'playing' && !showLeaderboard"
                :current-round="room.current_round"
                :total-rounds="room.total_rounds"
                :time-limit="room.time_limit"
                :max-guesses="room.max_guesses"
                :started-at="roundStartedAt"
                :history="history"
                :submitting="submitting"
                :solved="solved"
                :max-guesses-reached="maxGuessesReached"
                :guesses-used="guessesUsed"
                :round-score="roundScore"
                @guess="submitGuess"
                @time-expired="onTimeExpired"
            />

            <!-- Round Leaderboard -->
            <RoundLeaderboard
                v-else-if="showLeaderboard && !tournamentFinished"
                :round-number="currentLeaderboard.round_number"
                :total-rounds="room.total_rounds"
                :secret-code="currentLeaderboard.secret_code"
                :leaderboard="currentLeaderboard.leaderboard"
                :player-id="player.id"
                :is-host="player.is_host"
                :is-last-round="currentLeaderboard.is_last_round"
                @next-round="startNextRound"
            />
            <!-- Tournament Finished -->
            <TournamentFinished
                v-else-if="tournamentFinished"
                :standings="finalStandings"
                :player-id="player.id"
            />
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import axios from "axios";
import BackLink from "@/Components/BackLink.vue";
import GameCard from "@/Components/GameCard.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import RoundPlaying from "@/Components/GrandPrix/RoundPlaying.vue";
import RoundLeaderboard from "@/Components/GrandPrix/RoundLeaderboard.vue";
import TournamentFinished from "@/Components/GrandPrix/TournamentFinished.vue";

const props = defineProps({
    room: Object,
    player: Object,
    players: Array,
});

const room = ref(props.room);
const player = ref(props.player);
const players = ref(props.players);

const copied = ref(false);
const starting = ref(false);
const submitting = ref(false);
const solved = ref(false);
const maxGuessesReached = ref(false);
const guessesUsed = ref(0);
const roundScore = ref(0);
const history = ref([]);
const roundStartedAt = ref(null);
const showLeaderboard = ref(false);
const tournamentFinished = ref(false);
const currentLeaderboard = ref(null);
const finalStandings = ref([]);

let channel = null;
let pollInterval = null;

function copyCode() {
    navigator.clipboard.writeText(room.value.code);
    copied.value = true;
    setTimeout(() => (copied.value = false), 2000);
}

async function startTournament() {
    starting.value = true;
    try {
        await axios.post(route("grand-prix.start", room.value.code));
    } catch (e) {
        starting.value = false;
    }
}

async function startNextRound() {
    try {
        await axios.post(route("grand-prix.start", room.value.code));
    } catch (e) {}
}

async function submitGuess(guess) {
    submitting.value = true;
    try {
        const { data } = await axios.post(
            route("grand-prix.guess", room.value.code),
            { guess },
        );
        history.value.unshift({
            guess,
            dead: data.dead,
            injured: data.injured,
        });
        guessesUsed.value = data.guesses_used;

        if (data.solved) {
            solved.value = true;
            roundScore.value = data.score;
        } else if (data.max_guesses_reached) {
            maxGuessesReached.value = true;
        }
    } finally {
        submitting.value = false;
    }
}

async function onTimeExpired() {
    if (solved.value || maxGuessesReached.value) return;
    maxGuessesReached.value = true;

    // Host triggers round end
    if (player.value.is_host) {
        try {
            await axios.post(route("grand-prix.finish-round", room.value.code));
        } catch (e) {}
    }
}

function resetRoundState() {
    solved.value = false;
    maxGuessesReached.value = false;
    guessesUsed.value = 0;
    roundScore.value = 0;
    history.value = [];
    showLeaderboard.value = false;
}

function startPolling() {
    clearInterval(pollInterval);
    pollInterval = setInterval(async () => {
        if (room.value.status === "finished") {
            stopPolling();
            return;
        }
        try {
            const { data } = await axios.get(
                route("grand-prix.status", room.value.code),
            );
            if (data.players) players.value = data.players;
            if (data.status !== room.value.status) {
                room.value.status = data.status;
                if (data.status === "playing") {
                    starting.value = false;
                }
            }
        } catch (e) {}
    }, 3000);
}

function stopPolling() {
    clearInterval(pollInterval);
}

onMounted(() => {
    startPolling();

    channel = window.Echo.channel(`room.${room.value.code}`);

    channel.listen(".PlayerJoined", (e) => {
        if (!players.value.find((p) => p.id === e.player.id)) {
            players.value.push(e.player);
        }
    });

    channel.listen(".RoundStarted", (e) => {
        resetRoundState();
        room.value.status = "playing";
        room.value.current_round = e.round_number;
        roundStartedAt.value = new Date().toISOString();
        starting.value = false;
    });

    channel.listen(".RoundFinished", (e) => {
        showLeaderboard.value = true;
        currentLeaderboard.value = e;
        if (e.is_last_round) {
        }
    });

    channel.listen(".TournamentFinished", (e) => {
        tournamentFinished.value = true;
        finalStandings.value = e.final_standings;
    });
});

onUnmounted(() => {
    stopPolling();
    channel?.stopListening(".PlayerJoined");
    channel?.stopListening(".RoundStarted");
    channel?.stopListening(".RoundFinished");
    channel?.stopListening(".TournamentFinished");
});
</script>
