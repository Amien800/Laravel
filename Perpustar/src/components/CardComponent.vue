<template>
  <div class="container mt-20">
    <div class="flex flex-1 items-center justify-center p-6">
      <div class="w-full max-w-lg">
        <form class="mt-5 sm:flex sm:items-center">
          <input
            id="q"
            name="q"
            class="inline w-full rounded-md border border-gray-300 bg-white py-2 pl-3 pr-3 leading-5 placeholder-gray-500 focus:border-indigo-500 focus:placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm"
            placeholder="Keyword"
            type="search"
            autofocus=""
            value=""
            v-model="searchQuery"
          />
          <button
            type="submit"
            class="mt-3 inline-flex w-full items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
          >
            Search
          </button>
        </form>
      </div>
    </div>
    <div class="max-w-screen-xl mx-auto p-5 sm:p-10 md:p-16">
      <div class="border-b mb-5 flex justify-between text-sm">
        <div
          class="text-indigo-600 flex items-center pb-2 pr-2 border-b-2 border-indigo-600 uppercase"
        >
          <svg
            class="h-6 mr-3"
            version="1.1"
            id="Capa_1"
            xmlns="http://www.w3.org/2000/svg"
            xmlns:xlink="http://www.w3.org/1999/xlink"
            x="0px"
            y="0px"
            viewBox="0 0 455.005 455.005"
            style="enable-background: new 0 0 455.005 455.005"
            xml:space="preserve"
          >
            <g>
              <!-- SVG paths here -->
            </g>
          </svg>
          <a href="#" class="font-semibold inline-block">Daftar Buku</a>
        </div>
        <a href="#">See All</a>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-10">
        <!-- CARD 1 -->
        <div class="rounded overflow-hidden shadow-lg flex flex-col" v-for="card in filteredCards" :key="card.id">
          <div class="relative">
            <RouterLink :to="{ name: props.name, params: { id: card.id } }">
              <img
                class="w-full"
                :src="card.image"
                :alt="card.title"
              />
              <div
                class="hover:bg-transparent transition duration-300 absolute bottom-0 top-0 right-0 left-0 bg-gray-900 opacity-25"
              ></div>
            </RouterLink>
            <p v-if="card.stok > 0">
              <div
                class="text-xs absolute top-0 right-0 bg-indigo-600 px-4 py-2 text-white mt-3 mr-3 hover:bg-white hover:text-indigo-600 transition duration-500 ease-in-out"
              >
                Tersedia
              </div>
            </p>
            <p v-else>
              <div
                class="text-xs absolute top-0 right-0 bg-indigo-600 px-4 py-2 text-white mt-3 mr-3 hover:bg-white hover:text-indigo-600 transition duration-500 ease-in-out"
              >
                Tidak Tersedia
              </div>
            </p>
          </div>
          <div class="px-6 py-4 mb-auto">
            <RouterLink :to="{ name: props.name, params: { id: card.id } }" class="font-medium text-lg inline-block hover:text-indigo-600 transition duration-500 ease-in-out mb-2">
              {{ card.title }}
            </RouterLink>
            <p class="text-gray-500 text-sm">
              {{ card.summary.substring(0, 100) }}...
            </p>
          </div>
          <div class="px-6 py-3 flex flex-row items-center justify-between bg-gray-100">
            <span class="py-1 text-xs font-regular text-gray-900 mr-1 flex flex-row items-center">
              <svg
                height="13px"
                width="13px"
                version="1.1"
                id="Layer_1"
                xmlns="http://www.w3.org/2000/svg"
                xmlns:xlink="http://www.w3.org/1999/xlink"
                x="0px"
                y="0px"
                viewBox="0 0 512 512"
                style="enable-background: new 0 0 512 512"
                xml:space="preserve"
              >
                <g>
                  <g>
                    <path
                      d="M256,0C114.837,0,0,114.837,0,256s114.837,256,256,256s256-114.837,256-256S397.163,0,256,0z M277.333,256 c0,11.797-9.536,21.333-21.333,21.333h-85.333c-11.797,0-21.333-9.536-21.333-21.333s9.536-21.333,21.333-21.333h64v-128 c0-11.797,9.536-21.333,21.333-21.333s21.333,9.536,21.333,21.333V256z"
                    ></path>
                  </g>
                </g>
              </svg>
              <span class="ml-1">{{ card.stok }} Stok</span>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

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

const searchQuery = ref('');

// Filter books based on search query
const filteredCards = computed(() => {
  if (!searchQuery.value) {
    return props.cards;
  }
  return props.cards.filter(card =>
    card.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    card.summary.toLowerCase().includes(searchQuery.value.toLowerCase())
  );
});
</script>
