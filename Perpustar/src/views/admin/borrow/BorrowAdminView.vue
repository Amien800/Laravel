<template>
  <div>
    <NavbarAdminComponent />
    <div class="container my-28">
      <SectionTitle
        title="Peminjaman Buku Admin"
        content="Halaman Peminjaman Buku Admin"
        body="Halaman Edit, Detail  Peminjaman Buku"
      />

      <!-- Table Movies -->
      <div class="flex w-full items-center justify-center">
        <div class="overflow-x-auto">
          <table class="w-full shadow-md rounded-xl">
            <thead>
              <tr class="bg-blue-gray-100 text-bluebird">
                <th class="py-3 px-4 text-left">No</th>
                <th class="py-3 px-4 text-left">Tanggal Peminjaman</th>
                <th class="py-3 px-4 text-left">Tanggal Pengembalian</th>
                <th class="py-3 px-4 text-left">Nama Peminjam Buku</th>
                <th class="py-3 px-4 text-left">Buku Yang Dipinjam</th>
                <th class="py-3 px-4 text-left">Action</th>
              </tr>
            </thead>
            <tbody class="text-white">
              <tr
                v-for="(borr, index) in borrow"
                :key="borr.id"
                class="border-b border-blue-gray-200 text-bluebird"
              >
                <td class="py-3 px-4">{{ index + 1 }}</td>
                <td class="py-3 px-4">{{ borr.borrow_date }}</td>
                <td class="py-3 px-4">{{ borr.load_date }}</td>
                <td class="py-3 px-4">
                  {{ borr.user.name }}
                </td>
                <td class="py-3 px-4">{{ borr.books.title }}</td>

                <td class="py-3 px-4">
                  <RouterLink
                    :to="{
                      name: 'EditBorrowAdmin',
                      params: { id: borr.id },
                    }"
                    class="font-medium text-bluebird hover:text-primary"
                    >Edit</RouterLink
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
import FooterComponent from '@/components/FooterComponent.vue';
import SectionTitle from '@/components/SectionTitle.vue';
import customApi from '@/customApi';
import NavbarAdminComponent from '@/components/NavbarAdminComponent.vue';
import { useAuthStore } from '@/stores/auth';
const authStore = useAuthStore();

// Reactive variables
const user = ref(null);
const book = ref(null);
const borrow = reactive([]);

// Fetch all User
const fetchAllUser = async () => {
  try {
    const { data } = await customApi.get('/index', {
      headers: { Authorization: `Bearer ${authStore.tokenUser}` },
    });
    user.value = data.data.data;
  } catch (error) {
    console.error('Error fetching User:', error);
  }
};

// Fetch all Book
const fetchAllBook = async () => {
  try {
    const response = await customApi.get('/book');
    book.value = response.data.data;
  } catch (error) {
    console.error('Error fetching Books:', error);
  }
};

// Fetch all Borrow
const fetchAllBorrow = async () => {
  try {
    const { data } = await customApi.get('/borrow', {
      headers: { Authorization: `Bearer ${authStore.tokenUser}` },
    });
    borrow.splice(0, borrow.length, ...data.data); // Update the reactive array
    // console.log(data);
  } catch (error) {
    console.error('Error fetching Borrow Book:', error);
  }
};

// Lifecycle hook
onMounted(() => {
  fetchAllBook(), fetchAllBorrow(), fetchAllUser();
});
</script>
