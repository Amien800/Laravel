<template>
  <div>
    <NavbarAdminComponent />
    <div class="container">
      <SectionTitle
        class="mt-32"
        title="Kategori Admin"
        content="Halaman Kategori Admin"
        body="Halaman Tambah, Edit, Detail dan Hapus Kategori Buku"
      />
      <a
        class="bg-bluebird font-sans font-bold p-3 rounded-2xl float-right my-10 text-white"
        @click="openModal"
      >
        Tambah Kategori
      </a>
      <Transition name="grow-in" mode="out-in">
        <div v-if="showModal" class="modal modal-open">
          <div class="modal-box">
            <DialogCategoryComponent @closeModal="closeModal" />
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

      <!-- Table Genre -->
      <div class="flex w-full items-center justify-center mb-16">
        <div class="overflow-x-auto">
          <table class="w-full shadow-md rounded-xl">
            <thead>
              <tr class="bg-blue-gray-100 text-netflix">
                <th class="py-3 px-4 text-left text-bluebird">No</th>
                <th class="py-3 px-4 text-left text-bluebird">Nama Kategori</th>
                <th class="py-3 px-4 text-left text-bluebird">Link Gambar</th>
                <th class="py-3 px-4 text-left text-bluebird">Action</th>
              </tr>
            </thead>
            <tbody
              class="text-white"
              v-for="(category, index) in categories"
              :key="category.id"
              v-if="categories"
            >
              <tr class="border-b border-blue-gray-200">
                <td class="py-3 px-4 text-bluebird">{{ index + 1 }}</td>
                <td class="py-3 px-4 text-bluebird">{{ category.name }}</td>
                <td class="py-3 px-4 text-bluebird">{{ category.img_link }}</td>

                <td class="py-3 px-4">
                  <RouterLink
                    :to="{
                      name: 'EditCategoryAdmin',
                      params: { id: category.id },
                    }"
                    class="font-medium text-bluebird hover:text-sky-700"
                    >Edit</RouterLink
                  >
                </td>
                <td class="py-3 px-4">
                  <a
                    @click="openConfirmModal(category.id)"
                    class="font-medium text-bluebird hover:text-sky-700"
                  >
                    Delete
                  </a>
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
import { ref, onMounted } from 'vue';
import SectionTitle from '@/components/SectionTitle.vue';
import customApi from '@/customApi';
import { RouterLink } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import NavbarAdminComponent from '@/components/NavbarAdminComponent.vue';
import FooterComponent from '@/components/FooterComponent.vue';
import DialogCategoryComponent from '@/components/dialog/DialogCategoryComponent.vue';

const authStore = useAuthStore();

// Reactive variables
const showModal = ref(false);
const showConfirmModal = ref(false);
const categories = ref([]);
const categoryIdToDelete = ref(null); // ID kategori yang akan dihapus

// Fetch all Category
const fetchCategory = async () => {
  try {
    const { data } = await customApi.get('/category');
    categories.value = data.data;
  } catch (error) {
    console.error('Error fetching categories:', error);
  }
};

// Methods
const openModal = () => {
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  fetchCategory();
};

const openConfirmModal = (id) => {
  categoryIdToDelete.value = id;
  showConfirmModal.value = true;
};

const closeConfirmModal = () => {
  showConfirmModal.value = false;
  categoryIdToDelete.value = null;
};

const confirmDelete = async () => {
  try {
    await customApi.post(
      `/category/${categoryIdToDelete.value}?_method=DELETE`,
      null,
      {
        headers: { Authorization: `Bearer ${authStore.tokenUser}` },
      }
    );
    alert('Berhasil Delete Category');
    fetchCategory();
  } catch (error) {
    console.error('Error deleting category:', error);
  } finally {
    closeConfirmModal();
  }
};

// Lifecycle hook
onMounted(fetchCategory);
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
