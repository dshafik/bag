<script setup>
import {useData, useRoute} from "vitepress";
import { computed } from "vue";
import NotFound from "vitepress/dist/client/theme-default/NotFound.vue";

// Access the current route
const route = useRoute();

// Compute whether the current route is an old version
const isOldVersion = computed(() => {
  return route.path.match(/\/\d+\.(\d+|x)\//) !== null;
});

const { page } = useData()
</script>

<template>
  <div v-if="isOldVersion" class="old-version-warning warning custom-block github-alert">
    <p class="custom-block-content">
      You're browsing the documentation for an old version of Bag. Consider upgrading to
      <a href="/upgrading">the latest version</a>.
    </p>
  </div>
  <div v-if="page.isNotFound">
    <NotFound v-if="page.isNotFound" />
  </div>
</template>

<style>
.old-version-warning {
  padding-top: 8px;
  margin-bottom: 16px;
}

.old-version-warning .custom-block-content {
  text-align: center;
}

.old-version-warning a {
  text-decoration: underline;
}
</style>
