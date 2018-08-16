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
   window.events.$on('best-reply-deleted', () => {
      console.log('called');
      // this is too ofset the fact that if there are two replies
      // and one of them is the best reply, this counter will only count 1 reply
      // so if the best reply were deleted, then this counter would be hidden
      // this checks if the item deleted was a best reply and then increases the counter to prevent this from happenig 
      if(this.count === 1) {
        this.count = 2
      }
    });

    window.events.$on('deletedReply', () => {
      this.count--; 
    });
  }

}
</script>
