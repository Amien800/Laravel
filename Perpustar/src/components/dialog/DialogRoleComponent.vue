<template>
  <div>
    <p class="py-4 text-3xl">Tambah Role</p>
    <div>
      <form @submit.prevent="handleSubmit">
        <label class="input input-bordered flex items-center gap-2 mb-4">
          <input
            type="text"
            class="grow"
            placeholder="Masukkan Nama Role"
            v-model="name"
          />
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
import { ref, onMounted } from 'vue';

const authStore = useAuthStore();
const emit = defineEmits(['closeModal']);
const name = ref('');
const roles = ref([]); // Initialize Role as an empty array

// Fetch all Role
const fetchRole = async () => {
  try {
    const { data } = await customApi.get('/role', {
      headers: { Authorization: `Bearer ${authStore.tokenUser}` },
    });
    roles.value = data.data;
  } catch (error) {
    console.error('Error fetching genres:', error);
  }
};

// Handle form submission
const handleSubmit = async () => {
  try {
    await customApi.post(
      '/role',
      { name: name.value },
      {
        headers: { Authorization: `Bearer ${authStore.tokenUser}` },
      }
    );
    alert('Berhasil Tambah Data roles');
    fetchRole(); // Re-fetch genres after successful submission
    name.value = ''; // Clear the input field
  } catch (error) {
    console.error('Error adding Role:', error);
  }
};

// Lifecycle hook
onMounted(fetchRole);
</script>
