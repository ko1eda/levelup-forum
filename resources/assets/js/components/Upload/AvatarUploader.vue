<template>

  <div class="">
    <div class="tw-flex tw-flex-col img-box tw-justify-between tw-items-center">

      <span class="tw-text-2xl"> Preview </span>

      <div v-if="uploading">
        Processing...
      </div>
      <div v-else>
        <img :src="avatarPath" alt="profile picture" class="tw-h-48 tw-w-48">
      </div>

    
      <p v-if="this.errors.length > 0" class="help is-danger tw-text-sm"> {{ this.errors[0] }}</p>
      <!-- list the first error if there is one -->

      <div class="file">
        <label class="file-label">
          <input class="file-input" type="file" name="avatar" @change="onFileChange" accept="image/*">
          <span class="file-cta">
            <span class="file-icon">
              <i class="fas fa-upload"></i>
            </span>
            <span class="file-label">
              Choose a file…
            </span>
          </span>
        </label>
        <!-- end file input button -->

      </div>

      <!-- only display the hidden input with the avatar path if there is a successfully uploaded avatar path -->
      <input v-if="hasSuccessfulUpload" type="hidden" name="avatar_path" :value="rawAvatarPath" >

    </div>
  
  </div>

</template>



<script>
import upload from "../../ mixins/upload.js";

export default {
  mixins: [upload],


  props: {
    currentAvatar : {
      // type: String,
      required: true
    }
  },

  data () {
    return {
      avatarPath: this.currentAvatar ? this.appendsPath + this.currentAvatar : 'https://imgplaceholder.com/192x192/cccccc/757575/fa-user',
      rawAvatarPath: '',
      hasSuccessfulUpload : false,
      errors: [],
    }
  },

  methods: {
    onFileChange(e) {
      // If there are no files uploaded return 
      if(e.target.files.length === 0) return ;

      // Call the mixins upload method
      this.upload(e)
        .then(({data}) => {
          this.hasSuccessfulUpload = true;
          
          this.rawAvatarPath = data.path;

          // Append the base path to the file to the 
          // stored path 
          this.avatarPath = this.appendsPath + data.path;

          this.errors = []
        })
        .catch((error) => {
          // Get any errors for the given input key
          // ex get all errors for errors: {avatar: []}
          this.errors = error.response.data.errors[this.sendAs]
        })

    }
  }

};
</script>


<style lang="scss" scoped>
  .img-box {
    width: 100%;
    height: 300px;
  }
</style>
