<!-- DialogBorrowComponent.vue -->
<template>
  <div>
    <p class="py-4 text-3xl">Tambah Reviews</p>
    <div>
      <form @submit.prevent="handleSubmit">
        <label class="input input-bordered flex items-center gap-2 mb-4">
          <input type="date" class="grow" v-model="borrow.borrow_date" />
        </label>
        <input
          type="submit"
          class="grow bg-bluebird btn w-full mt-4 text-white hover:text-primary"
          value="Submit"
        />
      </form>
    </div>
    <div class="modal-action">
      <button class="btn" @click="$emit('closeModal')">Close Modal</button>
    </div>
  </div>
</template>

<script setup>
import customApi from '@/customApi';
import { useAuthStore } from '@/stores/auth';
import { reactive } from 'vue';
import { useRoute } from 'vue-router';
const route = useRoute();

const authStore = useAuthStore();
const emit = defineEmits(['closeModal']);

const borrow = reactive({
  borrow_date: '',
  load_date: '',
  book_id: route.params.id,
  user_id: authStore.currentUser.id,
});

const handleSubmit = async () => {
  try {
    let formData = new FormData();

    formData.append('borrow_date', borrow.borrow_date);
    formData.append('load_date', borrow.load_date);
    formData.append('book_id', borrow.book_id);
    formData.append('user_id', borrow.user_id);

    const newBorrow = await customApi.post('/borrow', formData, {
      headers: { Authorization: `Bearer ${authStore.tokenUser}` },
    });

    alert('Berhasil Meminjam Buku');
    borrow.borrow_date = '';
    console.log(newBorrow);
  } catch (error) {
    console.log(error);
  }
};
</script>
