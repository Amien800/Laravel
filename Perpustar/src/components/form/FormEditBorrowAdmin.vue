<template>
  <div>
    <div class="w-full px-4 pb-40 pt-24">
      <div class="mx-auto mb-16 mt-36 max-w-xl text-center">
        <div
          class="max-w-xl mx-auto text-center border border-gray-300 px-4 sm:px-8 py-10 rounded-xl shadow"
        >
          <header class="mb-8">
            <h1 class="text-3xl text-netflix font-bold mb-1">
              Form Edit Peminjaman Buku
            </h1>
            <p class="text-lg text-white">Edit Menu Peminjaman Buku</p>
          </header>
          <form @submit.prevent="handleSubmit">
            <div class="flex items-center justify-center gap-1 lg:gap-3">
              <label class="input input-bordered flex w-full gap-2 mb-4">
                <input
                  type="date"
                  class="grow"
                  placeholder="Masukkan Tanggal Peminjaman Buku"
                  v-model="borrow.borrow_date"
                />
              </label>
              <label class="input input-bordered flex w-full gap-2 mb-4">
                <input
                  type="date"
                  class="grow"
                  placeholder="Masukkan Tanggal Pengembalian Buku"
                  v-model="borrow.load_date"
                />
              </label>
            </div>

            <div class="flex w-full justify-center gap-1 lg:gap-3">
              <select
                class="select select-bordered w-full mb-4"
                v-if="user"
                v-model="borrow.user_id"
              >
                <option disabled selected>Pilih User</option>
                <option :value="user.id" v-for="user in user">
                  {{ user.name }}
                </option>
              </select>
            </div>

            <div class="flex w-full justify-center gap-1 lg:gap-3">
              <select
                class="select select-bordered w-full mb-4"
                v-if="book"
                v-model="borrow.book_id"
              >
                <option disabled selected>Pilih Buku</option>
                <option :value="book.id" v-for="book in book">
                  {{ book.title }}
                </option>
              </select>
            </div>

            <div class="w-full mx-auto mt-4">
              <input
                type="submit"
                class="w-full inline-flex justify-center whitespace-nowrap rounded-lg bg-netflix px-3.5 py-2.5 text-sm font-medium text-white shadow-sm shadow-indigo-950/10 hover:bg-indigo-600 focus:outline-none focus:ring focus:ring-indigo-300 focus-visible:outline-none focus-visible:ring focus-visible:ring-indigo-300 transition-colors duration-150 bg-bluebird"
                value="Edit Peminjaman Buku"
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

const borrow = ref({
  borrow_date: '',
  load_date: '',
  user_id: null,
  book_id: null,
});

const user = ref([]);
const book = ref([]);

// Fetch all User
const fetchAllUser = async () => {
  try {
    const { data } = await customApi.get('/index', {
      headers: { Authorization: `Bearer ${authStore.tokenUser}` },
    });
    user.value = data.data;
  } catch (error) {
    console.error('Error fetching User:', error);
  }
};

//fetch all book
const fetchAllBook = async () => {
  try {
    const response = await customApi.get('/book');
    book.value = response.data.data;
  } catch (error) {
    console.error('Error fetching book:', error);
  }
};

const fetchBorrowById = async () => {
  try {
    const { data } = await customApi.get(`/borrow/${route.params.id}`, {
      headers: { Authorization: `Bearer ${authStore.tokenUser}` },
    });
    Object.assign(borrow.value, data.data);
  } catch (error) {
    console.error('Error fetching borrow data:', error);
  }
};

const handleSubmit = async () => {
  try {
    const formData = new FormData();
    formData.append('load_date', borrow.value.load_date);
    formData.append('borrow_date', borrow.value.borrow_date);
    formData.append('user_id', borrow.value.user_id);
    formData.append('book_id', borrow.value.book_id);

    await customApi.post(`/borrow/${route.params.id}?_method=PUT`, formData, {
      headers: { Authorization: `Bearer ${authStore.tokenUser}` },
    });
    alert('Berhasil Update Borrow');
    router.push({ name: 'BorrowAdmin' });
    // Redirect to another route or show success message
  } catch (error) {
    console.error('Error updating Data:', error);
  }
};

onMounted(() => {
  fetchAllBook(), fetchAllUser(), fetchBorrowById();
});
</script>
