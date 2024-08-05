<script setup>
import CardComponent from '@/components/CardComponent.vue';
import FooterComponent from '@/components/FooterComponent.vue';
import HeroComponent from '@/components/HeroComponent.vue';
import NavbarComponent from '@/components/NavbarComponent.vue';

import { onMounted, reactive } from 'vue';
import customApi from '@/customApi';

const books = reactive([]);

// Fetch all movies
const fetchAllBooks = async () => {
  try {
    const response = await customApi.get('/book');
    books.splice(0, books.length, ...response.data.data); // Update the reactive array
  } catch (error) {
    console.error('Error fetching books:', error);
  }
};

// Lifecycle hook
onMounted(() => {
  fetchAllBooks();
});
</script>

<template>
  <main>
    <NavbarComponent />
    <HeroComponent />
    <CardComponent :cards="books" name="DetailBook" />
    <FooterComponent />
  </main>
</template>
