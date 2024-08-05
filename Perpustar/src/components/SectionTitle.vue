<template>
  <div class="w-full px-4 mt-32">
    <div class="mx-auto max-w-xl text-center">
      <h4 class="mb-2 text-lg font-semibold text-netflix mt-16 font-body">
        {{ props.title }}
      </h4>
      <h2
        class="mb-4 text-3xl font-bold text-netflix sm:text-4xl lg:text-5xl font-body"
      >
        {{ props.content }}
      </h2>
      <p class="text-md text-justify font-medium text-slate-500 md:text-lg">
        {{ props.body }}
      </p>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  title: {
    type: String,
    required: true,
  },
  content: {
    type: String,
    required: true,
  },
  body: {
    type: String,
    required: true,
  },
});
</script>
