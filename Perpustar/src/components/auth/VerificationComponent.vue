<template>
  <div class="w-full px-4 pb-40 pt-24">
    <div class="mx-auto mb-16 mt-36 max-w-xl text-center">
      <div
        class="max-w-md mx-auto text-center border-2 border-bluebird px-4 sm:px-8 py-10 rounded-xl shadow"
      >
        <header class="mb-8">
          <h1 class="text-2xl font-bold mb-1">Email Verification Code</h1>
          <p class="text-[15px] text-slate-500">
            Enter the 6-digit verification code that was sent to your email.
          </p>
          <div
            role="alert"
            class="alert alert-success"
            v-if="authStore.isError"
          >
            <span>{{ authStore.errMsg }}</span>
          </div>
        </header>
        <form @submit.prevent="handleVerifikasi()">
          <div class="flex items-center justify-center gap-1 lg:gap-3">
            <input
              type="text"
              class="w-60 h-8 lg:w-60 text-lg lg:h-14 text-center lg:text-2xl font-extrabold text-slate-900 bg-slate-100 border border-transparent hover:border-slate-200 appearance-none rounded lg:p-4 outline-none focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100"
              maxlength="6"
              v-model="otp.otp"
            />
          </div>
          <div class="max-w-[260px] mx-auto mt-4">
            <input
              type="submit"
              class="w-full inline-flex justify-center whitespace-nowrap rounded-lg bg-bluebird px-3.5 py-2.5 text-sm font-medium text-white shadow-sm shadow-indigo-950/10 hover:bg-indigo-600 focus:outline-none focus:ring focus:ring-indigo-300 focus-visible:outline-none focus-visible:ring focus-visible:ring-indigo-300 transition-colors duration-150"
              value="Verify Account"
            />
          </div>
        </form>
        <div class="text-sm text-slate-500 mt-4">
          Didn't receive code?
          <button
            type="submit"
            @click="handleGenerateOtp()"
            class="font-medium text-indigo-500 hover:text-indigo-600"
          >
            Resend
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useAuthStore } from '@/stores/auth';
import { reactive } from 'vue';
const otp = reactive({ otp: '' });
const authStore = useAuthStore();
const { generateOtp, verifikasiAkun } = authStore;

const handleGenerateOtp = () => {
  generateOtp();
  // console.log(authStore.currentUser.email);
};

const handleVerifikasi = () => {
  verifikasiAkun(otp);
};
</script>
