<template>
  <div v-if="visible">
    <hr>
    <h3 class="tw-text-xl sm:tw-text-2xl">Best Reply</h3>
  </div>
</template>

<script>
export default {
  props: {
 
    hasBest: {
      type: Boolean
    },
  },

  data() {
    return {
      visible : this.hasBest
    }
  },

  created() {
    window.events.$on('best-reply-deleted', () => {
      this.visible = false
    });
  }

}
</script>
