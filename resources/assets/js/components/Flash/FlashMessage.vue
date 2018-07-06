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
      type: String
    }
  },

  data() {
    return {
      display: false,
      body: this.message,
      level: "primary"
    };
  },

  methods: {
    /**
     * parses flash message string splitting
     * any laravel related flash messages by tilde
     * in the format ->with('flash', 'message~level')
     *
     * If the string contains no ~ (has a length of 1) it is assumed to
     * have been called through the our javascript flash function
     * and is therefore returned without setting the level -- as it would
     * have already been set when the flashEvent event was fired
     * 
     */
    parseSessionFlash(message) {
      let stringArray = message.split("~");

      if (stringArray.length === 1) return stringArray[0];
      
      this.setFlashLevel(stringArray[1]);
      
      return stringArray[0];
    },



    /**
     * sets the flash level (aka color of the message).
     */
    setFlashLevel(level) {
      this.level = level.toLowerCase();
    },



    /**
     * Display the flash message after it is parsed
     * then hide the message.
     */
    show(message) {
      this.body = this.parseSessionFlash(message);
      this.display = true;
      this.hide();
    },



    // Dismiss the message after
    // 3 seconds
    hide() {
      setTimeout(() => {
        this.display = false;
      }, 3000);
    }
  },



    /**
     * If a message exists from our laravel end, first display that message
     * 
     * Next add an event listener to listen for any flash events emitted from our global Vue eventbus.
     * If a level has been set, use that level then display the message.
     * 
     * If the level has not been set, set it to primary (default)
     */
    created() {
      if (this.message) this.show(this.message);

      window.events.$on("flashEvent", ({ message, level = null }) => {
        level 
          ? this.setFlashLevel(level) 
          : this.setFlashLevel('primary');

        this.show(message);
      });
    },
};
</script>



<style lang="scss" scoped>
.pos {
  position: fixed;
  bottom: 0.25rem;
  right: 0;
}
</style>