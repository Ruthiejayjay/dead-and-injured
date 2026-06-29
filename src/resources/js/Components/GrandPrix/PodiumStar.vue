<template>
    <img :src="starIcon" :class="sizeClass" alt="star" />
</template>
<script setup>
import starIcon from "@/assets/stars.svg";

const props = defineProps({
    size: { type: String, default: "sm" },
});

const sizeClass = props.size === "md" ? "w-12 h-4" : "w-12 h-4";
</script>
