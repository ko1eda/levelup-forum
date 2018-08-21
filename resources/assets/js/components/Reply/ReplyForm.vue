<script>
import atMentions from '../../ mixins/atMentions.js';

export default {

  mixins : [atMentions],


  props: {
    locked: {
      type: Boolean, 
      required: true
    }
  },


  data() {
    return {
      threadIsLocked: this.locked,
      threadIsEditing: false
    }
  },


  created() {
    // when the thread is locked hide the reply form
    // and display a message (message is in template)
    window.events.$on('thread-locked', () => {
      this.threadIsLocked = true;
    });

    window.events.$on('thread-unlocked', () => {
      this.threadIsLocked = false;
    });

    window.events.$on('thread-editing', () => {
      this.threadIsEditing = true;
    });

    window.events.$on('thread-editing-cancel', () => {
      this.threadIsEditing = false;
    });

  },

}
</script>

