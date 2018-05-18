<template>

  <div class="pos" v-if="display">
    <div class="lu-card notification is-primary sm:tw-w-64 tw-w-48 tw-py-4 tw-px-4 ">

      <div class="tw-flex tw-items-center tw-justify-between">
        <span>{{ message }} !</span>
        <button class="delete" @click="onDeleteClick"></button>
      </div>
    </div>
    
  </div>
</template>

<script>
export default {
  props: {
    message : {
      type: String,
    }
  },

  data() {
    return {
      body: this.message,
      display: false
    }
  },

  methods: {
    onDeleteClick() {
      this.display = false;
      console.log('called');
    }
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

