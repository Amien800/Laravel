<template>
  <div>
    <NavbarAdminComponent />
    <div class="container my-28 px-20">
      <SectionTitle
        title="Book Admin"
        content="Halaman Book Admin"
        body="Halaman Tambah, Edit, Detail dan Hapus Book"
      />
      <a
        class="bg-bluebird p-3 text-white font-sans rounded-xl float-right"
        @click="openModal"
      >
        Tambah Book
      </a>
      <Transition name="grow-in" mode="out-in">
        <div v-if="showModal" class="modal modal-open">
          <div class="modal-box">
            <DialogBookComponent
              @closeModal="closeModal"
              :category="category"
            />
          </div>
        </div>
      </Transition>

      <!-- Konfirmasi Hapus Modal -->
      <Transition name="fade" mode="out-in">
        <div v-if="showConfirmModal" class="modal modal-open">
          <div class="modal-box">
            <h2 class="text-xl font-bold">Konfirmasi Hapus</h2>
            <p>Apakah Anda yakin ingin menghapus kategori ini?</p>
            <div class="flex justify-end mt-4">
              <button
                @click="confirmDelete"
                class="bg-red-500 text-white p-2 rounded"
              >
                Hapus
              </button>
              <button
                @click="closeConfirmModal"
                class="bg-gray-300 text-black p-2 rounded ml-2"
              >
                Batal
              </button>
            </div>
          </div>
        </div>
      </Transition>

      <!-- Table books -->
      <div class="flex w-full items-center justify-center">
        <div class="overflow-x-auto">
          <table class="w-full shadow-md rounded-xl">
            <thead>
              <tr class="bg-blue-gray-100 text-netflix">
                <th class="py-3 px-4 text-left text-bluebird">No</th>
                <th class="py-3 px-4 text-left text-bluebird">Nama Buku</th>
                <th class="py-3 px-4 text-left text-bluebird">Summary</th>
                <th class="py-3 px-4 text-left text-bluebird">Gambar Buku</th>
                <th class="py-3 px-4 text-left text-bluebird">Kategori</th>
                <th class="py-3 px-4 text-left text-bluebird">Stok</th>
                <th class="py-3 px-4 text-left text-bluebird">Action</th>
              </tr>
            </thead>
            <tbody class="text-white">
              <tr
                v-for="(book, index) in books"
                :key="book.id"
                class="border-b border-blue-gray-200"
              >
                <td class="py-3 px-4 text-bluebird">{{ index + 1 }}</td>
                <td class="py-3 px-4 text-bluebird">{{ book.title }}</td>
                <td class="py-3 px-4 text-bluebird">
                  {{ book.summary.substring(0, 150) }}...
                </td>
                <td class="py-3 px-4">
                  <img
                    :src="book.image"
                    :alt="book.title"
                    class="w-[150px] h-[80px]"
                  />
                </td>
                <td class="py-3 px-4 text-bluebird">
                  {{ book.category.name }}
                </td>
                <td class="py-3 px-4 text-bluebird">{{ book.stok }}</td>

                <td class="py-3 px-4">
                  <RouterLink
                    :to="{
                      name: 'EditBookAdmin',
                      params: { id: book.id },
                    }"
                    class="font-medium text-bluebird hover:text-sky-700"
                    >Edit</RouterLink
                  >
                </td>
                <td class="py-3 px-4">
                  <a
                    @click="openConfirmModal(book.id)"
                    class="font-medium text-bluebird hover:text-sky-700"
                    >Delete</a
                  >
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <!-- end table -->
    </div>
    <FooterComponent />
  </div>
</template>

<script setup>
import { onMounted, reactive, ref } from 'vue';
import SectionTitle from '@/components/SectionTitle.vue';
import customApi from '@/customApi';
import { useAuthStore } from '@/stores/auth';
import NavbarAdminComponent from '@/components/NavbarAdminComponent.vue';
import FooterComponent from '@/components/FooterComponent.vue';
import DialogBookComponent from '@/components/dialog/DialogBookComponent.vue';

const authStore = useAuthStore();

// Reactive variables
const showModal = ref(false);
const showConfirmModal = ref(false);
const category = ref(null);
const books = reactive([]);
const bookIdToDelete = ref(null); // ID buku yang akan dihapus

// Fetch all categories
const fetchAllCategories = async () => {
  try {
    const response = await customApi.get('/category');
    category.value = response.data.data;
  } catch (error) {
    console.error('Error fetching categories:', error);
  }
};

// Fetch all books
const fetchAllBooks = async () => {
  try {
    const response = await customApi.get('/book');
    books.splice(0, books.length, ...response.data.data); // Update the reactive array
  } catch (error) {
    console.error('Error fetching books:', error);
  }
};

// Lifecycle hook
onMounted(() => {
  fetchAllBooks();
  fetchAllCategories();
});

// Methods
const openModal = () => {
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  fetchAllBooks();
};

const openConfirmModal = (id) => {
  bookIdToDelete.value = id;
  showConfirmModal.value = true;
};

const closeConfirmModal = () => {
  showConfirmModal.value = false;
  bookIdToDelete.value = null;
};

const confirmDelete = async () => {
  try {
    await customApi.post(`/book/${bookIdToDelete.value}?_method=DELETE`, null, {
      headers: { Authorization: `Bearer ${authStore.tokenUser}` },
    });
    alert('Berhasil Delete Book');
    fetchAllBooks();
  } catch (error) {
    console.error('Error deleting Book:', error);
  } finally {
    closeConfirmModal();
  }
};
</script>
<style scoped>
.modal-open {
  display: flex;
  align-items: center;
  justify-content: center;
}

.modal-box {
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}
</style>
