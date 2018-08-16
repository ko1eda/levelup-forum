<template>
  <div v-if="count > 0">
    <hr>
    <h3 class="tw-text-xl sm:tw-text-2xl">{{ this.label }}</h3>
  </div>
</template>

<script>
export default {
  props: {
    initialCount: {
      type: Number,
      required: true
    },
    label: {
      type: String,
      default: 'Replies'
    },
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
