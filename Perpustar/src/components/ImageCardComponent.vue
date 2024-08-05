<template>
  <div class="container mb-40">
    <div class="w-full">
      <div class="mx-auto max-w-7xl px-6 lg:px-8 pt-8">
        <div class="mx-auto max-w-2xl text-center">
          <h2
            class="text-3xl font-body font-bold tracking-tight text-bluebird sm:text-4xl"
          >
            Kategori Buku
          </h2>
        </div>
        <div
          class="mx-auto mt-8 grid max-w-2xl auto-rows-fr grid-cols-1 gap-8 sm:mt-12 lg:mx-0 lg:max-w-none lg:grid-cols-3"
        >
          <!-- First blog post -->
          <article
            class="relative isolate flex flex-col justify-end overflow-hidden rounded-2xl bg-gray-900 dark:bg-gray-700 px-8 py-8 pb-8 pt-80 sm:pt-48 lg:pt-80"
            v-for="card in cards"
            :key="card.id"
            v-if="cards.length"
          >
            <img
              :src="card.img_link"
              alt=""
              class="absolute inset-0 -z-10 h-full w-full object-cover"
            />
            <div
              class="absolute inset-0 -z-10 bg-gradient-to-t from-gray-900 via-gray-900/40"
            ></div>
            <div
              class="absolute inset-0 -z-10 rounded-2xl ring-1 ring-inset ring-gray-900/10"
            ></div>
            <div
              class="flex flex-wrap items-center gap-y-1 overflow-hidden text-sm leading-6 text-gray-300"
            ></div>
            <h3
              class="mt-3 text-lg font-semibold font-body leading-6 text-white"
            >
              <RouterLink
                :to="{
                  name: 'DetailCategory',
                  params: { id: card.id },
                }"
                class="text-4xl"
                >{{ card.name }}</RouterLink
              >
            </h3>
          </article>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { RouterLink } from 'vue-router';

const props = defineProps({
  cards: {
    type: Array,
    required: true,
  },
  name: {
    type: String,
    required: true,
  },
});
</script>
