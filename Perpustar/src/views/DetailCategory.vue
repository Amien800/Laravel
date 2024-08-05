<template>
  <div>
    <NavbarComponent />

    <div class="container">
      <SectionTitle
        class="mt-32"
        title="Detail Kategori Buku"
        content="Halaman Detail Kategori Buku"
        body=""
      />
      <RouterLink
        :to="{ name: 'Category' }"
        class="text-sm text-white font-bold btn bg-bluebird hover:bg-primary"
        >Kembali</RouterLink
      >
      <div
        v-if="categories && categories.name"
        class="border-b border-bluebird pb-4"
      >
        <h1 class="text-3xl text-bluebird font-bold mt-5">
          {{ categories.name }}
        </h1>
      </div>
      <div
        class="mx-auto mt-8 grid max-w-2xl auto-rows-fr grid-cols-1 gap-8 sm:mt-12 lg:mx-0 lg:max-w-none lg:grid-cols-3 mb-20"
        v-if="categories?.list_book?.length"
      >
        <!-- First blog post -->
        <article
          class="relative isolate flex flex-col justify-end h-[500px] lg:h-[550px] w-full overflow-hidden rounded-2xl bg-gray-900 dark:bg-gray-700 px-4 py-8 pb-8 pt-80 sm:pt-48 lg:pt-80"
          v-for="item in categories.list_book"
          :key="item.id"
        >
          <img
            :src="item.image"
            alt=""
            class="absolute inset-0 -z-10 h-full w-full object-cover opacity-40"
          />
          <div
            class="absolute inset-0 -z-10 bg-gradient-to-t from-gray-900 via-gray-900/40"
          ></div>
          <div
            class="absolute inset-0 -z-10 rounded-2xl ring-1 ring-inset ring-gray-900/10"
          ></div>
          <div
            class="flex flex-wrap items-center gap-y-1 overflow-hidden text-sm leading-6 text-gray-300"
          ></div>
          <h3 class="mt-3 text-lg font-semibold leading-6 text-white">
            <p><span class="absolute inset-0"></span>{{ item.title }}</p>
          </h3>
          <p class="mt-4 text-white font-body">
            {{ item.summary }}
          </p>
        </article>
      </div>
      <div v-else>
        <h1 class="text-2xl text-bluebird font-bold">
          Tidak Ada Buku Pada Kategori Ini
        </h1>
      </div>
    </div>
    <FooterComponent />
  </div>
</template>

<script setup>
import FooterComponent from '@/components/FooterComponent.vue';
import NavbarComponent from '@/components/NavbarComponent.vue';
import SectionTitle from '@/components/SectionTitle.vue';
import customApi from '@/customApi';
import { onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();

// Initialize categories as an empty object
const categories = ref('');

const categoryByID = async () => {
  try {
    const { data } = await customApi.get(`/category/${route.params.id}`);
    // Assign fetched data to categories
    categories.value = data.data;
    // console.log(data);
  } catch (error) {
    console.error('Error fetching genre data:', error);
  }
};

onMounted(() => {
  categoryByID();
});
</script>
