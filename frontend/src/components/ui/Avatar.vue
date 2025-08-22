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
defineOptions({
  name: 'UiAvatar'
})

const props = defineProps<{
  photo?: string
  initials: string
}>()
</script>

<style scoped>
.avatar {
  width: 40px;
  height: 40px;
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
  background: #f8f7fd;
  color: #3F37C9;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  font-size: 1.1rem;
}
</style>
