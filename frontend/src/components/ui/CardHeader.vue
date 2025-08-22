<template>
  <div class="card-header">
    <slot />
  </div>
</template>

<style scoped>
  .card-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 16px;
    color: #262626;
  }
</style>
