<template>
  <div class="tw-mr-4 tw-align-center">
    <span v-show="isLocked" @click="unlock">
      <i class="fas fa-lock tw-text-red-light tw-cursor-pointer" title="The thread is locked"></i>
    </span>
    <span v-show="!isLocked" @click="lock">
      <i class="fas fa-lock-open tw-text-bulma-light hover:tw-text-red-light tw-cursor-pointer" title="Lock the thread"></i>
    </span>
  </div>
</template>


<script>
export default {
  props : {
    endpoint : {
      type: String
    },

    locked : {
      type: Boolean,
    }
  },

  data () {
    return {
      isLocked: this.locked
    }
  },


  methods: {
    lock () {
      axios.post(this.endpoint)
        .then((res) => {

          window.events.$emit('thread-locked');

          this.isLocked = true;

        });
    },

    unlock () {
      axios.delete(this.endpoint)
        .then((res) => {

          window.events.$emit('thread-unlocked');

          this.isLocked = false;

        });
    }

  }


}
</script>
