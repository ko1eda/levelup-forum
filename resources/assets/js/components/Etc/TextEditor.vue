<template>
  <div>
      <quill-editor 
        v-model="content" 
        :options="options" 
        :class="this.size" 
        @input="onInput"  
        @focus='onFocus' 
        @blur='onBlur' 
        ref="editor">
      </quill-editor>
      <input type="hidden" :name="this.name" :value="replaced">
      <p class="tw-mt-1 tw-text-right tw-text-sm" v-if="this.label"> Supports Markdown </p>
  </div>
</template>


<script>
import 'quill/dist/quill.core.css';
import 'quill/dist/quill.snow.css';
import {debounce} from 'lodash';
import Quill from 'quill'
import { quillEditor } from 'vue-quill-editor';
import MarkdownShortcuts from 'quill-markdown-shortcuts-for-vue-quill-editor'
Quill.register('modules/markdownShortcuts', MarkdownShortcuts);

export default {
  components: {
    quillEditor
  },

  props: {
    name : {
      type: String,
      default: 'body'
    },

    toolbar: {
      type: Boolean,
      default: true
    },

    // hide the supports markdown label 
    label : {
      type : Boolean,
      default : true
    },

    animation : {
      type : Boolean,
      default : true
    },

    placeholder : {
      type: String,
      default : 'What\'s on your mind...'
    },

    supportsMentions : {
      type: Boolean,
      default: false
    },

    // used for v-sync with threads and replies
    body: {},

    height: {
      type: Array, 
      default: function () {
        return ['tw-h-48']
      } 
    },

  },

  // hide the toolbar if desired
  created () {
    this.options.modules.toolbar = false;
  },


  data () {
    return {
      content: this.body,
      replaced: this.content,
      size: this.height,
      options: {
        modules :  {
         markdownShortcuts: {},
         toolbar: [
            ['bold', 'italic', 'underline', 'strike'],
            ['blockquote', 'code-block'],
          ]
        },
        placeholder: this.placeholder
      }
    }
  },

  methods : {
    onInput (event) {
      // replace line breaks from @mentions in
      // the hidden input
      if (this.supportsMentions) {
        this.replaced = this.content.replace(/<p><br><\/p>/g, '');
      } else {
        this.$emit('update:body', event);

        this.replaced = this.content;
      }

      this.$emit('input', [event, this.$refs.editor.quill.getText().slice(0, -1)])
    },

    // increase the size and transition it
    onFocus: debounce(function (event) {
       if(this.animation) {

          this.size = ['tw-h-64', 'motion'];
       }
    }, 100),

    // set the size back to default
    onBlur : debounce(function (event) {
       if(this.animation) {
        this.size = this.height.concat(['motion']);
     
       }
    }, 300)
  },

  watch: {
    body() {
      this.content = this.body;
    }
  }
}
</script>


<style lang="scss" scoped>
  .motion{
    transition: height 600ms cubic-bezier(0.68, -0.55, 0.265, 1.55);
  }
  
</style>
