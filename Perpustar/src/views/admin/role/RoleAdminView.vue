<template>
  <div>
    <NavbarAdminComponent />
    <div class="container">
      <SectionTitle
        class="mt-32"
        title="Role Admin"
        content="Halaman Role Admin"
        body="Halaman Tambah, Edit, Detail dan Hapus Role"
      />
      <button class="btn btn-error float-right mb-5" @click="openModal">
        Tambah Role
      </button>

      <!-- Transisi Hapus Modal -->
      <Transition name="grow-in" mode="out-in">
        <div v-if="showModal" class="modal modal-open">
          <div class="modal-box">
            <DialogRoleComponent @closeModal="closeModal" />
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

      <!-- Table Role -->
      <div class="flex w-full items-center justify-center">
        <div class="overflow-x-auto">
          <table class="w-full shadow-md rounded-xl mb-16">
            <thead>
              <tr class="bg-blue-gray-100 text-bluebird">
                <th class="py-3 px-4 text-left">No</th>
                <th class="py-3 px-4 text-left">Nama Role</th>
                <th class="py-3 px-4 text-left">Action</th>
              </tr>
            </thead>
            <tbody class="text-white">
              <tr
                v-for="(role, index) in roles"
                :key="role.id"
                class="border-b border-blue-gray-200 text-bluebird"
              >
                <td class="py-3 px-4">{{ index + 1 }}</td>
                <td class="py-3 px-4">{{ role.name }}</td>
                <td class="py-3 px-4">
                  <RouterLink
                    :to="{ name: 'EditRoleAdmin', params: { id: role.id } }"
                    class="font-medium text-netflix hover:text-primary"
                  >
                    Edit
                  </RouterLink>
                </td>
                <td class="py-3 px-4">
                  <a
                    @click="openConfirmModal(role.id)"
                    class="font-medium text-netflix hover:text-primary cursor-pointer"
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
import { onMounted, ref } from 'vue';
import FooterComponent from '@/components/FooterComponent.vue';
import SectionTitle from '@/components/SectionTitle.vue';
import customApi from '@/customApi';
import { RouterLink } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import DialogRoleComponent from '@/components/dialog/DialogRoleComponent.vue';
import NavbarAdminComponent from '@/components/NavbarAdminComponent.vue';
const authStore = useAuthStore();

// Reactive variables
const showModal = ref(false);
const roles = ref([]); // Initialize roles as an empty array
const showConfirmModal = ref(false);
const roleIdToDelete = ref(null); // ID kategori yang akan dihapus

// Fetch all roles
const fetchRoles = async () => {
  try {
    const { data } = await customApi.get('/role', {
      headers: { Authorization: `Bearer ${authStore.tokenUser}` },
    });
    roles.value = data.data; // Correctly assign the fetched data to roles
  } catch (error) {
    console.error('Error fetching roles:', error);
  }
};

const openConfirmModal = (id) => {
  roleIdToDelete.value = id;
  showConfirmModal.value = true;
};

const closeConfirmModal = () => {
  showConfirmModal.value = false;
  roleIdToDelete.value = null;
};

const confirmDelete = async () => {
  try {
    await customApi.post(`/role/${roleIdToDelete.value}?_method=DELETE`, null, {
      headers: { Authorization: `Bearer ${authStore.tokenUser}` },
    });
    alert('Berhasil Delete Role');
    fetchRoles();
  } catch (error) {
    console.error('Error deleting Role:', error);
  } finally {
    closeConfirmModal();
  }
};

const openModal = () => {
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  fetchRoles();
};

// Lifecycle hook
onMounted(fetchRoles);
</script>
