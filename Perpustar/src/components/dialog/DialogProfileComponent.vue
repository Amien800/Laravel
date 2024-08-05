<template>
  <div>
    <p class="py-4 text-3xl">Update Profile</p>
    <div>
      <form @submit.prevent="handleSubmit">
        <label class="input input-bordered flex items-center gap-2 mb-4">
          <input
            type="text"
            class="grow"
            placeholder="Masukkan umur user"
            v-model="profile.age"
          />
        </label>
        <textarea
          class="textarea textarea-bordered w-full mb-4"
          placeholder="Biodata User"
          v-model="profile.bio"
        ></textarea>
        <label class="input input-bordered flex items-center gap-2 mb-4">
          <input
            type="text"
            class="grow"
            placeholder="Alamat"
            v-model="profile.address"
          />
        </label>
        <input
          type="Submit"
          class="grow bg-bluebird btn w-full mt-4 text-white hover:bg-sky-700"
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

const profile = reactive({
  age: '',
  bio: '',
  address: '',
});

const handleSubmit = async () => {
  try {
    let formData = new FormData();

    formData.append('age', profile.age);
    formData.append('bio', profile.bio);
    formData.append('address', profile.address);

    await customApi.post('/profile', formData, {
      headers: { Authorization: `Bearer ${authStore.tokenUser}` },
    });

    alert('Berhasil Update Data Profile');
    profile.age = '';
    profile.bio = '';
    profile.address = '';
  } catch (error) {
    console.log(error);
  }
};
</script>
