<template>
  <div class="avatar">
    <div v-if="photo">
      <img :src="props.photo" alt="Avatar" />
    </div>
    <div v-else>
      <span class="avatar-initials">{{ props.initials }}</span>
    </div>
  </div>
</template>

<script setup lang="ts">
const props = defineProps<{
  photo?: string
  initials: string
}>()
</script>

<style scoped>
.avatar {
  margin-left: 16px;
  width: 40px;
  height: 40px;
  margin-right: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
}
.avatar img {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  object-fit: cover;
}
.avatar-initials {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: #ffffff;
  color: #3F37C9;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  font-size: 1.1rem;
}
</style>
