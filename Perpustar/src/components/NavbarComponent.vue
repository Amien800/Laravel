<template>
  <header class="fixed left-0 top-0 z-10 flex w-full items-center bg-base-100">
    <div class="container mx-auto">
      <div class="relative flex items-center justify-between">
        <div class="px-4">
          <RouterLink
            to="/"
            class="block py-6 text-lg font-bold text-secondary font-body"
          >
            PERPUSTAR
          </RouterLink>
        </div>
        <div class="flex items-center px-4">
          <button
            id="hamburger"
            name="hamburger"
            type="button"
            class="absolute right-4 block lg:hidden"
            @click="toggleMenu"
          >
            <span
              class="hamburger-line origin-top-left transition duration-300 ease-in-out"
              :style="{ backgroundColor: isDarkMode ? '#000000' : '#ffffff' }"
              :class="{ 'transform rotate-45': isMenuOpen }"
            ></span>
            <span
              class="hamburger-line transition duration-300 ease-in-out"
              :style="{ backgroundColor: isDarkMode ? '#000000' : '#ffffff' }"
              :class="{ 'opacity-0': isMenuOpen }"
            ></span>
            <span
              class="hamburger-line origin-bottom-left transition duration-300 ease-in-out"
              :style="{ backgroundColor: isDarkMode ? '#000000' : '#ffffff' }"
              :class="{ 'transform -rotate-45': isMenuOpen }"
            ></span>
          </button>

          <nav
            id="nav-menu"
            :class="`absolute right-4 top-full w-full rounded-lg bg-base-100 py-5 shadow-lg lg:static lg:block lg:max-w-full lg:bg-transparent lg:shadow-none ${
              isMenuOpen ? '' : 'hidden'
            }`"
          >
            <ul class="block lg:flex">
              <RouterLink
                to="/"
                class="mx-8 flex py-2 text-base font-semibold text-secondary hover:text-primary lg:mx-3 font-body"
              >
                <li class="group">Beranda</li>
              </RouterLink>
              <RouterLink
                :to="{ name: 'Category' }"
                class="mx-8 flex py-2 text-base font-semibold text-secondary hover:text-primary lg:mx-3 font-body"
              >
                <li class="group">Kategori</li>
              </RouterLink>
              <RouterLink
                :to="{ name: 'Book' }"
                class="mx-8 flex py-2 text-base font-semibold text-secondary hover:text-primary lg:mx-3 font-body"
              >
                <li class="group">Buku</li>
              </RouterLink>
              <RouterLink
                :to="{ name: 'Profile' }"
                v-if="
                  authStore.tokenUser && authStore.currentUser.email_verified_at
                "
                class="mx-8 flex py-2 text-base font-semibold text-secondary font-body hover:text-primary lg:mx-3"
              >
                <li class="group">Profile</li>
              </RouterLink>
              <RouterLink
                :to="{ name: 'Verification' }"
                v-if="
                  authStore.tokenUser &&
                  !authStore.currentUser.email_verified_at
                "
                class="mx-8 flex py-2 text-base font-semibold text-secondary font-body hover:text-primary lg:mx-3"
              >
                <li class="group">Verifikasi Akun</li>
              </RouterLink>
              <RouterLink
                :to="{ name: 'Login' }"
                v-if="!authStore.tokenUser"
                class="mx-8 mb-2 flex text-base font-semibold p-2 border border-bluebird rounded-md bg-white text-bluebird hover:text-white hover:bg-bluebird lg:mx-1 font-body"
              >
                <li class="group">Login</li>
              </RouterLink>
              <RouterLink
                :to="{ name: 'Register' }"
                v-if="!authStore.tokenUser"
                class="mx-8 mb-2 flex text-base font-semibold p-2 text-white border rounded-md bg-bluebird hover:text-white hover:bg-cyan-700 lg:mx-1 font-body"
              >
                <li class="group">Register</li>
              </RouterLink>
              <a
                @click="handleLogout()"
                v-if="authStore.tokenUser"
                class="mx-8 mb-2 flex text-base font-semibold p-2 text-white border rounded-md bg-bluebird hover:text-white hover:bg-cyan-700 lg:mx-1 font-body"
              >
                <li class="group">Logout</li>
              </a>
              <li class="group">
                <label class="swap swap-rotate mx-8 flex py-2 text-base">
                  <input
                    type="checkbox"
                    @change="toggleTheme"
                    :checked="isDarkMode"
                  />
                  <svg width="30" height="30">
                    <circle cx="15" cy="15" r="6" fill="currentColor" />
                    <line
                      id="ray"
                      stroke="currentColor"
                      stroke-width="2"
                      stroke-linecap="round"
                      x1="15"
                      y1="1"
                      x2="15"
                      y2="4"
                    ></line>
                    <use href="#ray" transform="rotate(45 15 15)" />
                    <use href="#ray" transform="rotate(90 15 15)" />
                    <use href="#ray" transform="rotate(135 15 15)" />
                    <use href="#ray" transform="rotate(180 15 15)" />
                    <use href="#ray" transform="rotate(225 15 15)" />
                    <use href="#ray" transform="rotate(270 15 15)" />
                    <use href="#ray" transform="rotate(315 15 15)" />
                  </svg>
                </label>
              </li>
            </ul>
          </nav>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import { useAuthStore } from '@/stores/auth';
import { ref, onMounted } from 'vue';
import { RouterLink } from 'vue-router';
const authStore = useAuthStore();

const handleLogout = () => {
  // Show confirmation dialog
  const isConfirmed = window.confirm('Are you sure you want to logout?');

  if (isConfirmed) {
    // Proceed with logout if confirmed
    authStore.logoutUser();
  }
};

const isMenuOpen = ref(false);
const toggleMenu = () => {
  isMenuOpen.value = !isMenuOpen.value;
};

const isDarkMode = ref(false);

const toggleTheme = (event) => {
  isDarkMode.value = event.target.checked;
  document.documentElement.setAttribute(
    'data-theme',
    isDarkMode.value ? 'lofi' : 'night'
  );
  localStorage.setItem('theme', isDarkMode.value ? 'lofi' : 'night');
};

onMounted(() => {
  const savedTheme = localStorage.getItem('theme');
  if (savedTheme) {
    isDarkMode.value = savedTheme === 'lofi';
    document.documentElement.setAttribute('data-theme', savedTheme);
  } else {
    // Default theme
    isDarkMode.value = false;
    document.documentElement.setAttribute('data-theme', 'night');
  }
});
</script>

<style scoped>
.hamburger-line {
  display: block;
  width: 25px;
  height: 3px;
  margin: 5px auto;
  transition: all 0.3s ease-in-out;
}
.swap {
  cursor: pointer;
}
</style>
