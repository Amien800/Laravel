<script setup>
import { RouterView } from 'vue-router';
</script>

<template>
  <div>
    <RouterView v-slot="{ Component }">
      <transition name="grow-in" mode="out-in">
        <component :is="Component" />
      </transition>
    </RouterView>
  </div>
</template>

<style>
.grow-in-enter-active,
.grow-in-leave-active {
  transition: 0.3s ease-out;
}
.grow-in-enter-from,
.grow-in-leave-to {
  opacity: 0;
  transform: scale(0.3);
}
</style>
