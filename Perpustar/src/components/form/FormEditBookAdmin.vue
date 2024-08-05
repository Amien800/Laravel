<template>
  <div>
    <div class="w-full px-4 pb-40 pt-24">
      <div class="mx-auto mb-16 mt-36 max-w-xl text-center">
        <div
          class="max-w-xl mx-auto text-center border border-gray-300 px-4 sm:px-8 py-10 rounded-xl shadow"
        >
          <header class="mb-8">
            <h1 class="text-3xl text-netflix font-bold mb-1">Form Edit Buku</h1>
            <p class="text-lg text-white">Edit Menu Buku</p>
          </header>
          <form @submit.prevent="handleSubmit">
            <div class="flex items-center justify-center gap-1 lg:gap-3">
              <label class="input input-bordered flex w-full gap-2 mb-4">
                <input
                  type="text"
                  class="grow"
                  placeholder="Masukkan title"
                  v-model="book.title"
                />
              </label>
            </div>
            <div class="flex w-full justify-center gap-1 lg:gap-3">
              <textarea
                class="textarea textarea-bordered w-full h-2/3 mb-4"
                placeholder="Summary"
                v-model="book.summary"
              ></textarea>
            </div>
            <div class="flex w-full justify-center gap-1 lg:gap-3">
              <label class="input input-bordered flex w-full gap-2 mb-4">
                <input
                  type="text"
                  class="grow"
                  placeholder="Stok"
                  v-model="book.stok"
                />
              </label>
            </div>
            <div class="flex w-full justify-center gap-1 lg:gap-3">
              <select
                class="select select-bordered w-full mb-4"
                v-if="category"
                v-model="book.category_id"
              >
                <option disabled selected>Pilih Kategori Buku</option>
                <option :value="category.id" v-for="category in category">
                  {{ category.name }}
                </option>
              </select>
            </div>
            <div class="flex w-full justify-center gap-1 lg:gap-3">
              <input
                type="file"
                class="file-input file-input-bordered w-full"
                @change="handlePoster"
              />
            </div>
            <div class="w-full mx-auto mt-4">
              <input
                type="submit"
                class="w-full inline-flex justify-center whitespace-nowrap rounded-lg bg-netflix px-3.5 py-2.5 text-sm font-medium text-white shadow-sm shadow-indigo-950/10 hover:bg-indigo-600 focus:outline-none focus:ring focus:ring-indigo-300 focus-visible:outline-none focus-visible:ring focus-visible:ring-indigo-300 transition-colors duration-150 bg-bluebird"
                value="Edit book"
              />
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import customApi from '@/customApi';
import router from '@/router';
import { useAuthStore } from '@/stores/auth';
import { onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();
const authStore = useAuthStore();

const book = ref({
  title: '',
  summary: '',
  stok: '',
  category_id: null,
  image: null, // Added poster to the book object
});

const category = ref([]);

// Fetch all Categories
const fetchAllCategories = async () => {
  try {
    const response = await customApi.get('/category');
    category.value = response.data.data;
  } catch (error) {
    console.error('Error fetching categories:', error);
  }
};

const fetchBookById = async () => {
  try {
    const { data } = await customApi.get(`/book/${route.params.id}`);
    Object.assign(book.value, data.data);
  } catch (error) {
    console.error('Error fetching book data:', error);
  }
};

const handlePoster = (event) => {
  const file = event.target.files[0];
  if (file) {
    book.value.image = file;
  }
};

const handleSubmit = async () => {
  try {
    const formData = new FormData();
    formData.append('title', book.value.title);
    formData.append('summary', book.value.summary);
    formData.append('stok', book.value.stok);
    formData.append('category_id', book.value.category_id);
    if (book.value.image) {
      formData.append('image', book.value.image);
    }

    await customApi.post(`/book/${route.params.id}?_method=PUT`, formData, {
      headers: { Authorization: `Bearer ${authStore.tokenUser}` },
    });
    alert('Berhasil Update Data');
    router.push({ name: 'BookAdmin' });
    // Redirect to another route or show success message
  } catch (error) {
    console.error('Error updating book:', error);
  }
};

onMounted(() => {
  fetchAllCategories();
  fetchBookById();
});
</script>

<style scoped>
/* Add any additional styles if needed */
</style>
