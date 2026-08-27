<template>
    <div
        class="min-h-screen flex flex-col items-center justify-center bg-[#dce9f0] px-4 py-10"
    >
        <div class="w-full max-w-md">
            <div class="mb-6">
                <BackLink href="/play" />
            </div>

            <RoomWaiting
                v-if="room.status === 'waiting'"
                :code="room.code"
                :player="player"
                :opponent="opponent"
            />

            <RoomPlaying
                v-else-if="room.status === 'playing'"
                :opponent="opponent"
                :elapsed-ms="elapsedMs"
                :opponent-guesses="opponentGuesses"
                :history="history"
                :submitting="submitting"
                :paused="paused"
                @guess="submitGuess"
                @toggle-pause="togglePause"
            />

            <RoomResult
                v-if="gameResult"
                :result="gameResult"
                :opponent="opponent"
                :elapsed-ms="elapsedMs"
                :guesses-count="history.length"
                play-again-route="race.index"
            />
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import axios from "axios";
import BackLink from "@/Components/BackLink.vue";
import RoomWaiting from "@/Components/Duel/RoomWaiting.vue";
import RoomPlaying from "@/Components/Duel/RoomPlaying.vue";
import RoomResult from "@/Components/Duel/RoomResult.vue";

const props = defineProps({
    room: Object,
    player: Object,
    opponent: Object,
});

const room = ref(props.room);
const player = ref(props.player);
const opponent = ref(props.opponent);
const paused = ref(false);

const elapsedMs = ref(0);
const opponentGuesses = ref(0);
const history = ref([]);
const submitting = ref(false);
const gameResult = ref(null);
let timerInterval = null;
let channel = null;
let pollInterval = null;

function startTimer() {
    clearInterval(timerInterval);
    const start = Date.now() - elapsedMs.value;
    timerInterval = setInterval(
        () => (elapsedMs.value = Date.now() - start),
        10,
    );
}

function stopTimer() {
    clearInterval(timerInterval);
}

function togglePause() {
    if (paused.value) {
        paused.value = false;
        startTimer();
    } else {
        paused.value = true;
        stopTimer();
    }
}

function startPolling() {
    clearInterval(pollInterval);
    pollInterval = setInterval(async () => {
        if (
            room.value.status !== "waiting" &&
            room.value.status !== "playing"
        ) {
            stopPolling();
            return;
        }
        try {
            const { data } = await axios.get(
                route("race.status", room.value.code),
            );

            if (data.opponent_guesses !== undefined) {
                opponentGuesses.value = data.opponent_guesses;
            }

            if (data.status !== room.value.status) {
                room.value.status = data.status;
                if (data.opponent) opponent.value = data.opponent;
                if (data.status === "playing") startTimer();
                if (data.status === "finished" && !gameResult.value) {
                    stopTimer();
                    gameResult.value = {
                        won: data.winner_id === player.value.id,
                        winner_name: data.winner_name,
                        winner_guesses: data.winner_guesses,
                    };
                    stopPolling();
                }
            }
        } catch (e) {}
    }, 2000);
}

function stopPolling() {
    clearInterval(pollInterval);
}

async function submitGuess(guess) {
    submitting.value = true;
    try {
        const { data } = await axios.post(
            route("race.guess", room.value.code),
            { guess },
        );
        history.value.unshift({
            guess,
            dead: data.dead,
            injured: data.injured,
        });
        if (data.won) {
            stopTimer();
            stopPolling();
            gameResult.value = { won: true };
        }
    } finally {
        submitting.value = false;
    }
}

onMounted(() => {
    startPolling();

    if (room.value.status === "playing") startTimer();

    channel = window.Echo.channel(`room.${room.value.code}`);

    channel.listen(".PlayerJoined", (e) => {
        opponent.value = e.player;
    });

    channel.listen(".GameStarted", () => {
        room.value.status = "playing";
        startTimer();
    });

    channel.listen(".GuessMade", (e) => {
        if (e.player_id !== player.value.id) {
            opponentGuesses.value = e.guesses_count;
        }
    });

    channel.listen(".GameFinished", (e) => {
        stopTimer();
        stopPolling();
        const iWon = e.winner_id === player.value.id;
        gameResult.value = {
            won: iWon,
            winner_name: e.winner_name,
            winner_guesses: e.winner_guesses,
        };
    });
});

onUnmounted(() => {
    stopTimer();
    stopPolling();
    channel?.stopListening(".PlayerJoined");
    channel?.stopListening(".GameStarted");
    channel?.stopListening(".GuessMade");
    channel?.stopListening(".GameFinished");
});
</script>
