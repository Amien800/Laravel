<template>
  <div>
    <NavbarComponent />
    <SectionTitle
      class="mt-32"
      title="Detail Buku"
      content="Halaman Detail Buku"
      body=""
    />

    <div
      class="flex px-6 md:px-20 items-center justify-center bg-hero md:h-screen overflow-hidden mb-10"
      v-if="book"
    >
      <div class="flex flex-col gap-6 md:flex-row items-center max-w-8xl">
        <div class="w-full md:w-1/2 lg:pr-32">
          <h2
            class="text-6xl lg:text-6xl text-center md:text-left text-netflix leading-tight font-medium font-body"
          >
            {{ book.title }}
          </h2>
          <h2
            class="text-lg lg:text-2xl text-center md:text-left text-netflix leading-tight font-medium mt-6"
          >
            {{ book.stok }} copies available
          </h2>
          <h2
            class="text-lg lg:text-2xl text-center md:text-left text-netflix leading-tight font-medium mt-4 font-serif"
          >
            Category: {{ book.category.name }}
          </h2>
          <h2
            class="text-lg lg:text-2xl text-center md:text-left text-netflix leading-tight font-medium mt-3 font-mono"
          >
            {{ book.summary }}
          </h2>
          <div
            class="mt-10 flex flex-col sm:flex-row justify-center md:justify-start"
          >
            <button
              class="w-full sm:w-40 px-4 py-3 rounded font-semibold text-md bg-blue-500 text-white border-2 border-blue-500"
              v-if="
                authStore.tokenUser && authStore.currentUser.email_verified_at
              "
              @click="openModal"
            >
              Pinjam Buku
            </button>
            <!-- Transition -->
            <Transition name="grow-in" mode="out-in">
              <div v-if="showModal" class="modal modal-open">
                <div class="modal-box">
                  <DialogBorrowComponent @closeModal="closeModal" />
                </div>
              </div>
            </Transition>
          </div>
        </div>
        <div
          class="w-full h-[500px] md:w-1/2 flex justify-center md:justify-end"
        >
          <img :src="book.image" alt="Book Cover" />
        </div>
      </div>
    </div>
    <FooterComponent />
  </div>
</template>
<script setup>
import DialogBorrowComponent from '@/components/dialog/DialogBorrowComponent.vue';
import FooterComponent from '@/components/FooterComponent.vue';
import NavbarComponent from '@/components/NavbarComponent.vue';
import SectionTitle from '@/components/SectionTitle.vue';
import customApi from '@/customApi';
import { useAuthStore } from '@/stores/auth';
import { onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';

const authStore = useAuthStore();
const showModal = ref(false);
const route = useRoute();
const book = ref(null);

const fetchBookById = async () => {
  try {
    const response = await customApi.get(`/book/${route.params.id}`);
    book.value = response.data.data;
    // console.log('Book data:', book.value);
  } catch (error) {
    console.error('Error fetching book data:', error);
  }
};

onMounted(() => {
  fetchBookById();
});

const openModal = () => {
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  fetchBookById(); // Refresh book details if needed
};
</script>
