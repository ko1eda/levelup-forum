<template>
  <div class="lu-card tw-shadow tw-border tw-border-grey tw-p-6 md:tw-p-8">
    <div class="tw-flex tw-mb-6 tw-justify-center tw-max-w-full">
      <div class="tw-flex tw-flex-col tw-items-center tw-justify-center tw-w-1/3 tw-mr-4 md:tw-mr-2 ">
        <div class="tw-rounded-full tw-border tw-border-bulma-darker tw-h-24 tw-w-24 md:tw-h-32 md:tw-w-32 tw-overflow-hidden tw-mb-1">
          <img :src="this.data[0].profile.profile_photo_path" alt="">
        </div>
        <p class="tw-text-xl tw-font-light">
          <span v-text=" this.data[0].username" class=""></span>
        </p>
      </div>

      <div class="tw-flex tw-flex-col tw-justify-between ">
        <span class="tw-text-base md:tw-text-2xl md:tw-font-light ">Requested a new channel!</span>
        <p class="tw-text-sm md:tw-text-base ">
          <span>Name:</span>
          <span v-text="this.data[1].name" class=""></span>
        </p>
        <p class="tw-text-sm md:tw-text-base">
          <span>Tagline: </span>
          <span v-text="this.data[1].description" class="tw-"></span>
        </p>

        <p></p>
        <p></p> <!-- for spacing -->

      </div><!-- end name and description -->

    </div><!-- end first col -->

    <div class="tw-">   
      <div class="field is-grouped is-grouped-centered">
        <p class="control">
          <a class="button is-primary tw-w-32 md:tw-w-48 is-small">
            Approve
          </a>
        </p>

        <p class="control">
          <a class="button is-danger tw-w-32 md:tw-w-48 is-small">
            Decline
          </a>
        </p>
      </div>
    </div><!-- end buttons -->
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
