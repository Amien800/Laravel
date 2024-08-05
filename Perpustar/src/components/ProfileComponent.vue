<template>
  <div>
    <section
      id="card-movie"
      class="bg-base-100 my-8 pb-10 pt-14 px-8 lg:px-48 md:px-32"
    >
      <div>
        <div
          class="bg-base-200 mt-16 overflow-hidden mx-auto lg:w-1/2 shadow-xl rounded-lg border"
        >
          <div class="px-4 py-5 flex sm:px-6 sm:grid sm:grid-cols-3 sm:gap-4">
            <div class="avatar">
              <div class="w-20 rounded-full">
                <img
                  src="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp"
                />
              </div>
            </div>
            <h3
              class="text-lg leading-6 text-bluebird font-body font-bold mt-10 mx-6 lg:mx-1"
            >
              {{ user.name }}
            </h3>
          </div>
          <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
            <dl class="sm:divide-y sm:divide-gray-200">
              <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm text-bluebird font-body font-bold">
                  Email address
                </dt>
                <dd class="mt-1 text-sm text-bluebird sm:mt-0 sm:col-span-2">
                  {{ user.email }}
                </dd>
              </div>
              <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm text-bluebird font-body font-bold">
                  Status Verifikasi
                </dt>
                <dd class="mt-1 text-sm text-bluebird sm:mt-0 sm:col-span-2">
                  {{ statusVerifikasi }}
                </dd>
              </div>
              <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm text-bluebird font-body font-bold">Umur</dt>
                <dd class="mt-1 text-sm text-bluebird sm:mt-0 sm:col-span-2">
                  {{ user.age }}
                </dd>
              </div>
              <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm text-bluebird font-body font-bold">
                  Alamat
                </dt>
                <dd class="mt-1 text-sm text-bluebird sm:mt-0 sm:col-span-2">
                  {{ user.address }}
                </dd>
              </div>
              <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm text-bluebird font-body font-bold">
                  Biodata
                </dt>
                <dd class="mt-1 text-sm text-bluebird sm:mt-0 sm:col-span-2">
                  {{ user.bio }}
                </dd>
              </div>
              <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm text-bluebird font-body font-bold">
                  History Peminjaman Buku
                </dt>
                <dd
                  v-if="user.history_borrow.length"
                  class="mt-1 text-sm text-bluebird sm:mt-0 sm:col-span-2"
                  v-for="item in user.history_borrow"
                >
                  {{ item.title }}
                </dd>
                <dd
                  class="mt-1 text-sm text-bluebird sm:mt-0 sm:col-span-2"
                  v-else
                >
                  User Belom Pernah Meminjam Buku
                </dd>
              </div>
              <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm text-bluebird font-body font-bold">
                  Tanggal Pengembalian Buku
                </dt>
                <dd
                  v-if="user.load_borrow.length"
                  class="mt-1 text-sm text-bluebird sm:mt-0 sm:col-span-2"
                  v-for="item in user.load_borrow"
                  :key="item.id"
                >
                  {{ getReturnDate(item.borrow_date) }}
                </dd>
                <dd
                  class="mt-1 text-sm text-bluebird sm:mt-0 sm:col-span-2"
                  v-else
                >
                  User Belom Pernah Meminjam Buku
                </dd>
              </div>
              <div class="py-3 sm:py-5 sm:gap-4 sm:px-6">
                <button
                  class="bg-bluebird p-2 rounded-3xl w-full text-white my-4 hover:bg-sky-700"
                  @click="openModal"
                >
                  Update User
                </button>
                <Transition name="grow-in" mode="out-in">
                  <div v-if="showModal" class="modal modal-open">
                    <div class="modal-box">
                      <DialogProfileComponent @closeModal="closeModal" />
                    </div>
                  </div>
                </Transition>
              </div>
            </dl>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { useAuthStore } from '@/stores/auth';
import { ref, reactive, onMounted } from 'vue';
import customApi from '@/customApi';
import DialogProfileComponent from '@/components/dialog/DialogProfileComponent.vue';

const statusVerifikasi = ref('');
// Reactive variables
const showModal = ref(false);
const profileStore = useAuthStore();
const user = reactive({
  name: '',
  email: '',
  age: '',
  email_verified_at: '',
  address: '',
  bio: '',
  history_borrow: [],
  load_borrow: [],
});

// Fetch all Cast
const fetchUser = async () => {
  try {
    const { data } = await customApi.get('/me', {
      headers: { Authorization: `Bearer ${profileStore.tokenUser}` },
    });
    user.email = data.user.email;
    user.name = data.user.name;
    user.email_verified_at = data.user.email_verified_at;
    user.age = data.user.profile.age;
    user.address = data.user.profile.address;
    user.bio = data.user.profile.bio;
    user.history_borrow = data.user.history_borrow;
    user.load_borrow = data.user.load_borrow;
    // console.log(data.user.load_borrow);
  } catch (error) {
    console.error('Error fetching cast:', error);
  }
};

if (user.email_verified_at != null) {
  statusVerifikasi.value = 'Sudah Verifikasi';
} else {
  statusVerifikasi.value = 'Belum Verifikasi';
}

onMounted(fetchUser);

const openModal = () => {
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  fetchUser();
};

const getReturnDate = (borrowDate) => {
  const monthNames = [
    'January',
    'February',
    'March',
    'April',
    'May',
    'June',
    'July',
    'August',
    'September',
    'October',
    'November',
    'December',
  ];

  const date = new Date(borrowDate);
  date.setDate(date.getDate() + 7);

  // Get day, month, and year
  const day = date.getDate();
  const monthIndex = date.getMonth();
  const year = date.getFullYear();

  // Format the date with English month names
  const formattedDate = `${day} ${monthNames[monthIndex]} ${year}`;

  return formattedDate;
};
</script>
