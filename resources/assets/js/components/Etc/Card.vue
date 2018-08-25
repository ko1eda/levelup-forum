<template>
  <div class="lu-card tw-p-6">
    <div class="tw-flex tw-mb-8">
      <div class="tw-w-1/2 tw-flex tw-justify-center tw-items-center tw-mr-4 tw-mt-2">
        <div class="tw-rounded-full tw-border tw-border-bulma-darkest tw-h-24 tw-w-24 md:tw-h-32 md:tw-w-32 tw-overflow-hidden">
          <img :src="this.data[0].profile.profile_photo_path" alt="">
        </div>
      </div>

      <div class="tw-w-full tw-flex tw-flex-col tw-justify-around ">
        <p class="tw-text-2xl md:tw-text-4xl tw-font-light tw-align-top">
          <span class="">@</span>
          <span v-text="this.data[0].username" class=""></span>
        </p>
        <p class="tw-text-sm md:tw-text-base">
          <span>Has rquested a new channel</span>
          <span v-text="'/' + this.data[1].name" class="tw-font-bold"></span>
        </p>
        <p class="tw-text-sm md:tw-text-base">
          <span>Description:</span>
          <span v-text="this.data[1].description" class="tw-"></span>
        </p>
      </div>
    </div>

    <div class="tw-pt-6">
      
      <div class="field is-grouped is-grouped-centered">
        <p class="control">
          <a class="button is-primary tw-w-32 lg:tw-w-48 is-small">
            Approve
          </a>
        </p>

        <p class="control">
          <a class="button is-danger tw-w-32 lg:tw-w-48 is-small">
            Decline
          </a>
        </p>
      </div>
      
    </div>
  </div>
</template>


<script>
export default {

  props : {
    data : {
      type : Array,
      required: true
    }
  },

  data () {
    return {
     
    }
  }
}
</script>
