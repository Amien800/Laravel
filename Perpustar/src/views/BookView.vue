<script setup>
import CardComponent from '@/components/CardComponent.vue';
import FooterComponent from '@/components/FooterComponent.vue';
import NavbarComponent from '@/components/NavbarComponent.vue';
import { onMounted, reactive, ref } from 'vue';
import customApi from '@/customApi';

const category = ref(null);
const books = reactive([]);

// Fetch all Category
const fetchAllCategories = async () => {
  try {
    const response = await customApi.get('/category');
    category.value = response.data.data;
  } catch (error) {
    console.error('Error fetching category:', error);
  }
};

// Fetch all books
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
  fetchAllCategories();
  fetchAllBooks();
});
</script>

<template>
  <main>
    <NavbarComponent />
    <CardComponent :cards="books" name="DetailBook" />
    <FooterComponent />
  </main>
</template>
