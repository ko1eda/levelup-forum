<template>
  <div class="lu-card tw-shadow tw-border tw-border-grey tw-p-8 ">
    <div class="tw-flex tw-mb-6 tw-justify-center tw-max-w-full">
      <div class="tw-flex tw-flex-col tw-items-center tw-justify-center tw-w-1/3 tw-mr-2 tw-mt-2">
        <div class="tw-rounded-full tw-border tw-border-bulma-darker tw-h-24 tw-w-24 md:tw-h-32 md:tw-w-32 tw-overflow-hidden tw-mb-1">
          <slot :data="this.data" name="photo"></slot>
        </div>
        <p class="tw-text-xl tw-font-light">
          <slot :data="this.data" name="username"></slot>
        </p>
      </div>

      <div class="tw-flex tw-flex-col tw-justify-between " style="width:270px">
        <slot :data="this.data" name="card-title"></slot>
        
        <slot name="body" :data="this.data"></slot>

        <p></p>
        <p></p>
        <p></p> <!-- for spacing -->
      </div><!-- end name and description -->

    </div><!-- end first col -->

    <div class="tw-">   
     <slot name="buttons" :approve="approve" :decline="decline"></slot>
    </div><!-- end buttons -->
  </div>
</template>


<script>
export default {

  props : {
    data : {
      type : Array,
      required: true
    },

    approveUri : {
      type : String
    },

    declineUri : {
      type : String
    }
  },

  methods: {
    approve () {
      let regex = /tokenID=(.+)/gi;

      axios.post(this.approveUri +  regex.exec(window.location.search)[0])
        .then(res => {
          flash('Channel approved, redirecting...');
          
          setTimeout(() => {
            window.location = "/threads";
          }, 500) 

        });
    },

    decline () {
      axios.post(this.declineUri)
        .then(res => {

        });
    }
  }
}
</script>
