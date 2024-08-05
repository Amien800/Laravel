<template>
  <div>
    <div class="w-full px-4 pb-40 pt-24">
      <div class="mx-auto mb-16 mt-36 max-w-xl text-center">
        <div
          class="max-w-xl mx-auto text-center border border-gray-300 px-4 sm:px-8 py-10 rounded-xl shadow"
        >
          <header class="mb-8">
            <h1 class="text-3xl text-netflix font-bold mb-1">
              Form Edit Category
            </h1>
            <p class="text-lg text-white">Edit Menu Category</p>
          </header>
          <form @submit.prevent="handleSubmit">
            <div class="flex items-center justify-center gap-1 lg:gap-3">
              <label class="input input-bordered flex w-full gap-2 mb-4">
                <input
                  type="text"
                  class="grow"
                  placeholder="Masukkan nama"
                  v-model="category.name"
                />
              </label>
            </div>
            <div class="flex w-full justify-center gap-1 lg:gap-3">
              <label class="input input-bordered flex w-full gap-2 mb-4">
                <input
                  type="text"
                  class="grow"
                  placeholder="Year"
                  v-model="category.img_link"
                />
              </label>
            </div>
            <div class="w-full mx-auto mt-4">
              <input
                type="submit"
                class="w-full inline-flex justify-center whitespace-nowrap rounded-lg bg-bluebird px-3.5 py-2.5 text-sm font-medium text-white shadow-sm shadow-indigo-950/10 hover:bg-indigo-600 focus:outline-none focus:ring focus:ring-indigo-300 focus-visible:outline-none focus-visible:ring focus-visible:ring-indigo-300 transition-colors duration-150"
                value="Edit Movie"
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

const category = ref({
  name: '',
  img_link: '',
});

const fetchCategoryById = async () => {
  try {
    const { data } = await customApi.get(`/category/${route.params.id}`);
    Object.assign(category.value, data.data);
  } catch (error) {
    console.error('Error fetching category data:', error);
  }
};

const handleSubmit = async () => {
  try {
    const formData = new FormData();
    formData.append('name', category.value.name);
    formData.append('img_link', category.value.img_link);

    await customApi.post(`/category/${route.params.id}?_method=PUT`, formData, {
      headers: { Authorization: `Bearer ${authStore.tokenUser}` },
    });
    alert('Berhasil Update Data category');
    router.push({ name: 'CategoryAdmin' });
    // Redirect to another route or show success message
  } catch (error) {
    console.error('Error updating category:', error);
  }
};

onMounted(() => {
  fetchCategoryById();
});
</script>

<style scoped>
/* Add any additional styles if needed */
</style>
