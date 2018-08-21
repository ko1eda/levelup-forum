<template>
  <div>
    <quill-editor v-model="content" :options="options" :class="this.height" @input="$emit('update:body', $event)"></quill-editor>
    <input type="hidden" :name="this.name" :value="content">
  </div>
</template>


<script>
import 'quill/dist/quill.core.css';
import 'quill/dist/quill.snow.css';
import { quillEditor } from 'vue-quill-editor';

export default {
  components: {
    quillEditor
  },

  props: {
    name : {
      type: String,
      default: 'body'
    },

    body: {},

    height: {
      type: Array, 
      default: function () {
        return ['tw-h-24']
      } 
    },

  },


  data () {
    return {
      content: this.body,
      options: {
        modules :  {
         toolbar: [
            ['bold', 'italic', 'underline', 'strike'],
            ['blockquote', 'code-block'],
            [{ 'list': 'ordered' }, { 'list': 'bullet' }],
            [{ 'indent': '-1' }, { 'indent': '+1' }],
            [{ 'align': [] }],
          ]
        },
      }
    }
  },

  watch: {
    body() {
      this.content = this.body;
    }
  }
}
</script>


<style lang="scss" scoped>

</style>
