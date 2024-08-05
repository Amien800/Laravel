<script setup>
import SectionTitle from '@/components/SectionTitle.vue';
import NavbarAdminComponent from '@/components/NavbarAdminComponent.vue';
import FooterComponent from '@/components/FooterComponent.vue';
</script>

<template>
  <main>
    <NavbarAdminComponent />
    <div class="container my-56">
      <SectionTitle
        title="Halaman Admin"
        content="Home Admin View"
        body="Halaman ini Merupakan Halaman Home Admin Yang Berisikan Tentang Dashboard Dari Admin"
      />
    </div>
    <FooterComponent />
  </main>
</template>
