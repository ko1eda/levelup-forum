<script>
import Reply from '../Reply/Reply.vue';
import ReplyForm from '../Reply/ReplyForm.vue';
import BestReplyDivider from '../Reply/BestReplyDivider.vue';
import ReplyDivider from '../Reply/ReplyDivider.vue';
import LockButton from './LockButton.vue';
import SubscribeButton from './SubscribeButton.vue';

export default {

    components: {
      'lu-reply' : Reply,
      'lu-reply-form': ReplyForm, 
      'lu-reply-divider' : ReplyDivider,
      'lu-best-reply-divider' : BestReplyDivider,
      'lu-lock-button' : LockButton,
      'lu-subscribe-button' : SubscribeButton,
    },

    props: {
      thread : {
        type: Object
      },

      endpoint : {
        type: String
      }
    },

    data () {
      return {
        editing: false,
        hasBeenEdited: false,
        body : this.thread.body,
        editedBody : this.thread.body,
        error: '',
      }
    },



  methods : {
    setEditing () {
      this.editing = true

      window.events.$emit('thread-editing');
    },

    unsetEditing () {
      this.editing = false;
      
      window.events.$emit('thread-editing-cancel');
    },

  
    handleUpdate () {
      // iF the user tries to submit the error message 
      if (this.error) {

        this.handleCancel();

        return
      }

      // if the user tries to resubmit the reply they've already left
      if(this.body === this.thread.body || this.body === this.editedBody) {

        this.handleCancel();

        return
      }

      // if all checks pass send the patch request
      // we only update the body because the anchored body is derrived from the regular body
      axios.patch(this.endpoint, {
        body: this.body
      })
      .then(({data}) => {
        this.body = data.body;

        this.hasBeenEdited = true;

        this.unsetEditing();

        flash('The Thread was updated');

        // set the edited body to the plain body
        // because when a user 
        // is editing they are only seeing the 
        // plain no linked body
        this.editedBody = data.body;
        })
        .catch(({response:{data:{errors:{body}}}}) => {
          this.body = body[0];
          this.error = body[0]

          flash('Hit cancel and try again', 'danger');
        });
    },

    clearErrors() {
      this.error = '';
    },

    resetBodyState() {
      if (this.hasBeenEdited) {
        this.body = this.editedBody;
      }
      else {
        this.body = this.thread.body;
      }
    },


    handleCancel() {
      this.resetBodyState();

      this.clearErrors();

      this.unsetEditing();
    },

  }
}
</script>
