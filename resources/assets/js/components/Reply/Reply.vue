
<script>
import Favorite from '../Favorite/Favorite.vue';
export default {

  components : {
    'lu-favorite' : Favorite
  },

  props: {
    attributes: {
      type: Object,
      required: true
    }
  },

  data () {
    return {
      editing: false,
      body: this.attributes.body,
      deleted: false,
      error: '',
      hasBeenEdited: false,
      editedBody: ''
    }
  },

  methods: {

    clearErrors() {
      this.error = '';
    },

    // this is called from reply cancel
    // if the body has already been patched it will
    // be returend 
    // otherwise the original body will be returned 
    resetBodyState() {
      if (this.hasBeenEdited) {
        this.body = this.editedBody;
      }
      else {
        this.body = this.attributes.body;
      }
    },

    // opens the editing pane
    // clears any errors that may have
    // been previously present
    handleEditing() {
      this.clearErrors();
      this.editing = true;
    },

    // Cancel a reply edit
    handleReplyCancel() {

      this.resetBodyState();

      this.clearErrors();
      
      // close the editing window
      this.editing = false;
    },

    handleReplyUpdate() {
      // IF the user tries to submit the error message 
      // return to the previously stored reply
      if (this.body === this.error) {

        this.handleReplyCancel();

        return
      }

      // if the user tries to resubmit the reply they've already left
      if(this.body === this.attributes.body || this.editedBody) {

        this.handleReplyCancel();

        return
      }

      // if all checks pass send the patch request
      axios.patch(`/replies/${this.attributes.id}`, {
        body: this.body
      })
        .then(({data}) => {
          this.body = data.body;

          this.hasBeenEdited = true;

          this.editing = false

          flash('Edited a Reply!');

          this.editedBody = this.body;
        })
        .catch(({response:{data:{errors:{body}}}}) => {
          this.body = body[0];
          this.error = body[0]
        });
    },
    
  
    // Delete the given reply
    // Then hide it
    // Emit a global deleted reply event
    // which will then update the vue reply counter
    // component 
    // Then flash a message 
    handleReplyDelete() {
      axios.delete(`/replies/${this.attributes.id}`)
        .then(() =>  {
          this.deleted = true;
          window.events.$emit('deletedReply');
          flash('Deleted a Reply!', 'danger')
        });
    }

  }
}
</script>
