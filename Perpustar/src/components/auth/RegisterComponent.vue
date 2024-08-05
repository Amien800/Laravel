<template>
  <div
    class="flex flex-col justify-center font-[sans-serif] sm:h-screen p-4 mt-36 mb-16"
  >
    <div
      class="max-w-md w-full mx-auto border-bluebird border-2 rounded-2xl p-8 shadow-xl ase-in-out delay-150 hover:-translate-y-1 hover:scale-110 hover:bg-base-100 duration-300"
    >
      <div class="text-center mb-12">
        <h1 class="inline-block text-bluebird text-3xl">Form Register</h1>
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
            <label class="text-bluebird text-sm mb-2 block">Name</label>
            <input
              name="name"
              type="text"
              class="text-bluebird bg-white border border-gray-300 w-full text-sm px-4 py-3 rounded-md outline-blue-500"
              placeholder="Enter name"
              v-model="userInput.name"
            />
          </div>
          <div>
            <label class="text-bluebird text-sm mb-2 block">Email</label>
            <input
              name="email"
              type="email"
              class="text-bluebird bg-white border border-gray-300 w-full text-sm px-4 py-3 rounded-md outline-blue-500"
              placeholder="Enter email"
              v-model="userInput.email"
            />
          </div>
          <div>
            <label class="text-bluebird text-sm mb-2 block">Password</label>
            <input
              name="password"
              type="password"
              class="text-bluebird bg-white border border-gray-300 w-full text-sm px-4 py-3 rounded-md outline-blue-500"
              placeholder="Enter password"
              v-model="userInput.password"
            />
          </div>
          <div>
            <label class="text-bluebird text-sm mb-2 block"
              >Confirm Password</label
            >
            <input
              name="cpassword"
              type="password"
              class="text-bluebird bg-white border border-gray-300 w-full text-sm px-4 py-3 rounded-md outline-blue-500"
              placeholder="Enter confirm password"
              v-model="userInput.password_confirmation"
            />
          </div>
        </div>

        <div class="!mt-12">
          <input
            type="submit"
            class="w-full py-3 px-4 text-sm tracking-wider font-semibold rounded-md text-white bg-bluebird hover:bg-sky-600 focus:outline-none"
            value="Register"
          />
        </div>
        <p class="text-bluebird text-sm mt-6 text-center">
          Already have an account?
          <RouterLink
            :to="{ name: 'Login' }"
            class="ml-1 text-base font-bold text-bluebird hover:text-sky-800"
            >Login Here</RouterLink
          >
        </p>
      </form>
    </div>
  </div>
</template>

<script setup>
import { useAuthStore } from '@/stores/auth';
import { reactive } from 'vue';
const authStore = useAuthStore();

const { regisUser } = authStore;

const userInput = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
});

const handleAuth = () => {
  regisUser(userInput);

  userInput.name = '';
  userInput.email = '';
  userInput.password = '';
  userInput.password_confirmation = '';
};
</script>
