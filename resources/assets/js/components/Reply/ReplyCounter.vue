<template>
  <p class="lu-pannel-text"> Replies: {{ this.count }} </p>
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
