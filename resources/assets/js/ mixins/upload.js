export default {

  props: {
    
    endpoint : {
      type: String,
      required: true
    },

    sendAs : {
      type: String,
      required: true
    },

    // This is the appended 
    // path to the file you are uploading. It is the root path.
    // For instance laravel stores local files 
    // in the storage directory,
    // this will append that directory name
    // to your file path
    // in the UI only
    appendsPath : {
      type: String,
      default: '/storage/'
    }

  },

  data () {
    return {
      uploading: false
    }
  },


   methods: {

     upload(e) {
      this.uploading = true; 

      // upload the image to this.endpoint
      // then return a promise so that the class using the mixin can handle the response
      return axios.post(this.endpoint, this.packageUploads(e))
        .then((res) => {
          this.uploading = false;

          return Promise.resolve(res)
        })
        .catch((error) => {
          this.uploading = false;

          return Promise.reject(error)
        })
     },

     // Create a new formData object to append
     // any files to the end of the formdata object
     // the formdata object represents whatever form we are submitting
     // so it will append any file we upload to that form. If the sendAs key already exists
     // then it will append the file to the end of the object for that key instead of overriding the value that is there
     packageUploads(e) {
       let fileData = new FormData();

       fileData.append(this.sendAs, e.target.files[0]);

       return fileData;
     }


   }

}