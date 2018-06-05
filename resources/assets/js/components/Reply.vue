
<script>
export default {

  props: {
    attributes: {
      type: Object,
      required: true
    }
  },

  data () {
    return {
      editing: false,
      body: this.attributes.body
    }
  },

  methods: {
    // Send patch request to endpoint
    // Update reply body with edited content
    // returned from the response
    // Change editing to false to close the edit form 
    handleReplyUpdate() {
      axios.patch(`/replies/${this.attributes.id}`, {
        body: this.body
      })
        .then(({data}) => {
          this.body = data.body;
          this.editing = false
        });
    },

    handleReplyCancel() {
      this.body = this.attributes.body;
      this.editing = false;
    }

  }

}
</script>
