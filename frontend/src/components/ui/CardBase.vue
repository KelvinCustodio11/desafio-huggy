<template>
  <div class="card-base">
    <slot />
  </div>
</template>

<style scoped>
  .card-base {
    min-height: 90vh;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 16px rgba(0,0,0,0.08);
    padding: 16px;
    width: 930px;
    flex-direction: column;
    margin-top: 12px;
  }
</style>
