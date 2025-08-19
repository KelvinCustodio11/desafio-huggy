<script setup lang="ts">
defineProps<{ label: string, type?: 'button' | 'submit' }>()
</script>

<template>
  <button
    :type="type || 'button'"
    class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700 transition"
  >
    {{ label }}
  </button>
</template>

<script lang="ts">
export default {
  name: "UiButton"
}
</script>
