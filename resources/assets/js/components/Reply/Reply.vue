
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
    },

    bestUri: {
      type: String,
      required: true
    },

    hasBest: {
      type: Boolean,
      required: true
    }
  },

  data () {
    return {
      editing: false,
      body: this.attributes.body,
      anchoredBody: this.attributes.anchored_body,
      deleted: false,
      error: '',
      hasBeenEdited: false,
      editedBody: '',
      editedAnchoredBody: '',
      isMarkedBest: this.hasBest
    }
  },

    // Any reply whose id does not match the id 
    // from the event emitted on our markBest method
    // will have their isMarkedBest property set to false
    // only one isMarkedBest can be set at a time 
   created() {
      window.events.$on('best-reply-selected', (id) => {

        this.isMarkedBest = id === this.attributes.id;
      });
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
        this.anchoredBody = this.editedAnchoredBody;
      }
      else {
        this.body = this.attributes.body;
        this.anchoredBody = this.attributes.anchored_body;
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
      if(this.body === this.attributes.body || this.body === this.editedBody) {

        this.handleReplyCancel();

        return
      }

      // if all checks pass send the patch request
      // we only update the body because the anchored body is derrived from the regular body
      axios.patch(`/replies/${this.attributes.id}`, {
        body: this.body
      })
        .then(({data}) => {
          // set body to new plain body
          this.body = data.body;

          // set anchoredBody to returned new anchored body
          this.anchoredBody = data.anchored_body;

          this.hasBeenEdited = true;

          this.editing = false

          flash('Edited a Reply!');

          // set the edited body to the plain body
          // because when a user is editing they are only seeing the 
          // plain no linked body
          this.editedBody = data.body;
          
          // set the editedAnchoredBody so that when 
          // the body is updated on edit, the anchored body
          // which is what displays the tags, is also updated
          // this is used in the resetBodyState function
          this.editedAnchoredBody = data.anchored_body;

        })
        .catch(({response:{data:{errors:{body}}}}) => {
          this.body = body[0];
          this.error = body[0]
        });
    },
    
    // Mark the best reply
    handleMarkBest() {
      // Send a post request to endpoint
      axios.post(this.bestUri)
        .then(res => {
          
          this.isMarkedBest = true;

          window.events.$emit('best-reply-selected', this.attributes.id);

          flash('Updating the best reply');

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


<style lang="scss" scoped>
@import '../../../sass/vendor/_bulma-theme.scss';

// The deep selector is a css selector used
// to select dynamically loaded content like v-html 
  #style-link {
    /deep/ a {
      color: $link;
    }
  }

</style>
