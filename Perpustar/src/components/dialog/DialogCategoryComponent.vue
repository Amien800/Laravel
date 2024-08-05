<template>
  <div>
    <p class="py-4 text-3xl">Tambah category</p>
    <div>
      <form @submit.prevent="handleSubmit">
        <label class="input input-bordered flex items-center gap-2 mb-4">
          <input
            type="text"
            class="grow"
            placeholder="Masukkan nama Kategori"
            v-model="category.name"
          />
        </label>
        <label class="input input-bordered flex items-center gap-2 mb-4">
          <input
            type="text"
            class="grow"
            placeholder="Link Gambar"
            v-model="category.img_link"
          />
        </label>
        <input
          type="Submit"
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

const authStore = useAuthStore();

const emit = defineEmits(['closeModal']);

const category = reactive({
  name: '',
  img_link: '',
});

const handleSubmit = async () => {
  try {
    let formData = new FormData();

    formData.append('name', category.name);
    formData.append('img_link', category.img_link);

    await customApi.post('/category', formData, {
      headers: { Authorization: `Bearer ${authStore.tokenUser}` },
    });

    alert('Berhasil Tambah Data category');
    category.name = '';
    category.img_link = '';
  } catch (error) {
    console.log(error);
  }
};
</script>
