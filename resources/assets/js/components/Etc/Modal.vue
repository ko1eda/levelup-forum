<template>
  <div>
    <slot name="trigger" :handle-open="handleOpen" :is-Open="isOpen"></slot>
   
    <div class="modal tw-px-2 md:tw-px-0" v-if="isOpen">
      <div class="overlay" @click="isOpen = false"></div><!-- end overlay -->

      <div class="tw-rounded tw-bg-white tw-shadow tw-z-50 " :style="this.cardStyling">
        <div class="tw-flex tw-items-center tw-justify-center tw-text-xl tw-h-16 tw-bg-blue tw-text-center tw-text-white tw-rounded-t tw-border-b tw-border-grey-light tw-px-4">
          <div class="tw-flex-1" >
            <slot name="heading"></slot>
          </div>

          <a class="delete" @click="isOpen = false"></a>
        </div>

        <div class="tw-p-6">
          <slot name="body"></slot>
        </div>

    
        <p class="tw-flex tw-justify-center tw-p-6">
          <button type=submit class="tw-bg-red tw-text-white tw-py-2 tw-px-4 tw-outline-none tw-rounded hover:tw-bg-red-dark">
            Yes, delete my account
          </button>
        </p>
       
      </div><!-- end card -->

    </div>
  </div>
</template>


<script>
export default {
  
  props: {

    cardStyling : {
      type: Object,
      default () {
        return {
          'width': '32rem'
        }
      }
    },

    onOpen : {
      type: Function,
      default : function () {
          this.isOpen = true
        }
      }
  },

  data () {
    return {
      isOpen : false
    }
  },

  methods : {
    handleOpen () {
      return this.onOpen()
    }
  }

}
</script>


<style lang="postcss" scoped>
  .overlay {
    position: absolute;
    height: 100vh;
    width: 100%;
    z-index: 20;
    background: config(colors.bulma-darkest);
    opacity: .8;
  }

  .modal {
    position: fixed;
    top: 0px;
    left: 0px;
    display: flex;
    justify-content: center;
    align-items: center;
  }

</style>
