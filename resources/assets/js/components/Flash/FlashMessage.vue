<template>
  <div class="pos" v-if="display">
    <div class="lu-card notification sm:tw-w-64 tw-w-48 tw-py-4 tw-px-4 " :class="'is-'+level">

      <span>{{ body }}</span>

    </div>
  </div>
</template>

<script>
  export default {
    props: {
      message: {
        type: String,
      }
    },

    data() {
      return {
        display: false,
        body: this.message,
        level: 'primary'
      }
    },

    methods: {

      // set the body attribute to the 
      // incoming flash message value
      // display the notification
      // then set the hide timer
      show(message) {
        this.body = message;

        this.display = true

        this.hide();
      },

      // Dismiss the message after
      // 3 seconds
      hide() {
        setTimeout(() => {
          this.display = false
        }, 3000);
      },
    },

    // on a flash event from the global Vue eventbus
    // show the flash message
    created() {
      if(this.message)
        this.show(this.message);
    
      window.events.$on('flashEvent', ({message, level = null}) => {
        level && (this.level = level);
        this.show(message);
      });
    }

  }
</script>


<style lang="scss" scoped>
  .pos {
    position: fixed;
    bottom: .25rem;
    right: 0
  }
</style>