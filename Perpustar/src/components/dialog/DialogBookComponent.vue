<template>
  <div>
    <p class="py-4 text-3xl text-bluebird">Tambah Buku</p>
    <div>
      <form @submit.prevent="handleSubmit">
        <label class="input input-bordered flex items-center gap-2 mb-4">
          <input
            type="text"
            class="grow"
            placeholder="Masukkan title"
            v-model="book.title"
          />
        </label>
        <textarea
          class="textarea textarea-bordered w-full mb-4"
          placeholder="Summary"
          v-model="book.summary"
        ></textarea>
        <label class="input input-bordered flex items-center gap-2 mb-4">
          <input
            type="number"
            class="grow"
            placeholder="Stok"
            v-model="book.stok"
          />
        </label>
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
        <input
          type="file"
          class="file-input file-input-bordered w-full"
          @change="handlePoster"
        />
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

// Define props
const props = defineProps({
  category: {
    type: Array,
    required: true,
  },
});

const authStore = useAuthStore();

const emit = defineEmits(['closeModal']);

const book = reactive({
  title: '',
  summary: '',
  image: null,
  stok: '',
  category_id: '',
});

const handlePoster = (e) => {
  const selectImage = e.target.files[0];
  book.image = selectImage;
};

const handleSubmit = async () => {
  try {
    let formData = new FormData();

    formData.append('title', book.title);
    formData.append('summary', book.summary);
    formData.append('stok', book.stok);
    formData.append('category_id', book.category_id);
    formData.append('image', book.image);

    const newCategories = await customApi.post('/book', formData, {
      headers: { Authorization: `Bearer ${authStore.tokenUser}` },
    });

    alert('Berhasil Tambah Data Buku');
    book.title = '';
    book.summary = '';
    book.stok = '';
    book.category_id = '';
    book.image = null;
    console.log(newCategories);
  } catch (error) {
    console.log(error);
  }
};
</script>
