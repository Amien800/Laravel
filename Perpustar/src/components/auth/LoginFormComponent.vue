<template>
  <div
    class="flex flex-col justify-center font-[sans-serif] sm:h-screen p-4 mt-36 mb-16"
  >
    <div
      class="max-w-md w-full mx-auto border-2 border-sky-700 rounded-2xl p-8 shadow-xl ase-in-out delay-150 hover:-translate-y-1 hover:scale-110 hover:bg-base-100 duration-300"
    >
      <div class="text-center mb-12">
        <h1 class="inline-block text-bluebird text-3xl">Form Login</h1>
        <h1 class="text-bluebird text-2xl font-bold">PERPUSTAR</h1>
        <div
          v-if="authStore.isError"
          role="alert"
          class="alert alert-error mt-3"
        >
          <span>{{ authStore.errMsg }}</span>
        </div>
      </div>

      <form @submit.prevent="handleAuth()">
        <div class="space-y-6">
          <div>
            <label class="text-bluebird text-sm mb-2 block">Email</label>
            <input
              name="email"
              type="email"
              class="text-gray-800 bg-white border border-gray-300 w-full text-sm px-4 py-3 rounded-md outline-blue-500"
              placeholder="Enter email"
              v-model="userInput.email"
            />
          </div>
          <div>
            <label class="text-bluebird text-sm mb-2 block">Password</label>
            <input
              name="password"
              type="password"
              class="text-gray-800 bg-white border border-gray-300 w-full text-sm px-4 py-3 rounded-md outline-blue-500"
              placeholder="Enter password"
              v-model="userInput.password"
            />
          </div>
        </div>

        <div class="!mt-12">
          <input
            type="submit"
            class="w-full py-3 px-4 text-sm tracking-wider font-semibold rounded-md text-white bg-bluebird hover:bg-sky-600 focus:outline-none"
            value="Login"
          />
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { useAuthStore } from '@/stores/auth';
import { reactive } from 'vue';
const authStore = useAuthStore();

const { loginUser } = authStore;

const userInput = reactive({
  email: '',
  password: '',
});

const handleAuth = () => {
  loginUser(userInput);
  userInput.email = '';
  userInput.password = '';
};
</script>
