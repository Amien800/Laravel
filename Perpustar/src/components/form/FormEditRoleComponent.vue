<template>
  <div>
    <div class="w-full px-4 pb-40 pt-24">
      <div class="mx-auto mb-16 mt-36 max-w-xl text-center">
        <div
          class="max-w-md mx-auto text-center border border-gray-300 px-4 sm:px-8 py-10 rounded-xl shadow"
        >
          <header class="mb-8">
            <h1 class="text-2xl font-bold mb-1">Form Edit Role</h1>
            <p class="text-[15px] text-slate-500">Edit Menu Role</p>
          </header>
          <form @submit.prevent="handleSubmit()">
            <div class="flex items-center justify-center gap-1 lg:gap-3">
              <input
                type="text"
                class="w-60 h-8 lg:w-60 text-lg lg:h-14 text-center lg:text-2xl font-extrabold text-slate-900 bg-slate-100 border border-transparent hover:border-slate-200 appearance-none rounded lg:p-4 outline-none focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"
                v-model="name"
              />
            </div>
            <div class="max-w-[260px] mx-auto mt-4">
              <input
                type="submit"
                class="w-full inline-flex justify-center whitespace-nowrap rounded-lg bg-netflix px-3.5 py-2.5 text-sm font-medium text-white shadow-sm shadow-indigo-950/10 hover:bg-indigo-600 focus:outline-none focus:ring focus:ring-indigo-300 focus-visible:outline-none focus-visible:ring focus-visible:ring-indigo-300 transition-colors duration-150 bg-bluebird"
                value="Edit Role"
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

const name = ref('');

const roleById = async () => {
  try {
    const { data } = await customApi.get(`/role/${route.params.id}`, {
      headers: { Authorization: `Bearer ${authStore.tokenUser}` },
    });
    name.value = data.data.name;
  } catch (error) {
    console.error('Error fetching role data:', error);
  }
};

const handleSubmit = async () => {
  try {
    await customApi.post(
      `/role/${route.params.id}?_method=PUT`,
      {
        name: name.value,
      },
      {
        headers: { Authorization: `Bearer ${authStore.tokenUser}` },
      }
    );
    alert('Berhasil Update Data Role');
    router.push({ name: 'RoleAdmin' });
  } catch (error) {
    console.error('Error submitting edit:', error);
  }
};

onMounted(() => {
  roleById();
});
</script>
