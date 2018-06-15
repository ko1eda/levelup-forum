<template>
  <div v-if="count">
    <hr>
    <h3 class="tw-text-xl sm:tw-text-2xl">Replies:</h3>
  </div>
</template>

<script>
export default {
  props: {
    initialCount: {
      type: Number,
      required: true
    }
  },

  data() {
    return {
      count: this.initialCount
    }
  },

  created() {
    window.events.$on('deletedReply', () => {
      this.count--; 
    });
  }

}
</script>
