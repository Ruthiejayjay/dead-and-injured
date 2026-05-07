<template>
    <div
        class="min-h-screen flex flex-col items-center justify-center bg-[#dce9f0] px-4 py-10"
    >
        <div class="w-full max-w-md">
            <RoomWaiting
                v-if="room.status === 'waiting'"
                :code="room.code"
                :player="player"
                :opponent="opponent"
            />

            <RoomSetCode
                v-else-if="room.status === 'setting_codes'"
                :player="player"
                :opponent="opponent"
                @submit="submitCode"
            />

            <RoomPlaying
                v-else-if="room.status === 'playing'"
                :opponent="opponent"
                :elapsed-ms="elapsedMs"
                :opponent-guesses="opponentGuesses"
                :history="history"
                :submitting="submitting"
                @guess="submitGuess"
            />

            <RoomResult
                v-if="gameResult"
                :result="gameResult"
                :opponent="opponent"
                :elapsed-ms="elapsedMs"
                :guesses-count="history.length"
            />
        </div>
    </div>
</template>
<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import Echo from "@/echo.js";
import axios from "axios";
import RoomWaiting from "@/Components/Duel/RoomWaiting.vue";
import RoomSetCode from "@/Components/Duel/RoomSetCode.vue";
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

const elapsedMs = ref(0);
const opponentGuesses = ref(0);
const history = ref([]);
const submitting = ref(false);
const gameResult = ref(null);
let timerInterval = null;
let channel = null;

function startTimer() {
    const start = Date.now();
    timerInterval = setInterval(
        () => (elapsedMs.value = Date.now() - start),
        10,
    );
}

function stopTimer() {
    clearInterval(timerInterval);
}

async function submitCode(code) {
    await axios.post(route("duel.set-code", room.value.code), {
        secret_code: code,
    });
    player.value.ready = true;
}

async function submitGuess(guess) {
    submitting.value = true;
    try {
        const { data } = await axios.post(
            route("duel.guess", room.value.code),
            { guess },
        );
        history.value.unshift({
            guess,
            dead: data.dead,
            injured: data.injured,
        });
        if (data.won) {
            stopTimer();
            gameResult.value = { won: true };
        }
    } finally {
        submitting.value = false;
    }
}

onMounted(() => {
    channel = window.Echo.channel(`room.${room.value.code}`);

    channel.listen("PlayerJoined", (e) => {
        opponent.value = e.player;
        room.value.status = "setting_codes";
    });

    channel.listen("PlayerReady", (e) => {
        if (e.player_id !== player.value.id) {
            opponent.value.ready = true;
        }
    });

    channel.listen("GameStarted", () => {
        room.value.status = "playing";
        startTimer();
    });

    channel.listen("GuessMade", (e) => {
        if (e.player_id !== player.value.id) {
            opponentGuesses.value = e.guesses_count;
        }
    });

    channel.listen("GameFinished", (e) => {
        stopTimer();
        const iWon = e.winner_id === player.value.id;
        gameResult.value = {
            won: iWon,
            winner_name: e.winner_name,
            winner_guesses: e.winner_guesses,
        };
    });

    if (room.value.status === "playing") startTimer();
});

onUnmounted(() => {
    stopTimer();
    channel?.stopListening("PlayerJoined");
    channel?.stopListening("PlayerReady");
    channel?.stopListening("GameStarted");
    channel?.stopListening("GuessMade");
    channel?.stopListening("GameFinished");
});
</script>
