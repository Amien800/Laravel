<script setup>
import FooterComponent from '@/components/FooterComponent.vue';
import ImageCardComponent from '@/components/ImageCardComponent.vue';
import NavbarComponent from '@/components/NavbarComponent.vue';
import SearchComponent from '@/components/SearchComponent.vue';

import { onMounted, ref } from 'vue';
import customApi from '@/customApi';

const category = ref([]);

// Fetch all genres
const fetchCategory = async () => {
  try {
    const { data } = await customApi.get('/category');
    category.value = data.data;
    // console.log(data);
  } catch (error) {
    console.error('Error fetching genres:', error);
  }
};

// Lifecycle hook
onMounted(fetchCategory);
</script>

<template>
  <main>
    <NavbarComponent />
    <SearchComponent />
    <ImageCardComponent :cards="category" name="DetailCategory" />
    <FooterComponent />
  </main>
</template>
