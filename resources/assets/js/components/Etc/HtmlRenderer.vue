<template>

  <span v-html="this.toRender"></span>

</template>

<script>
export default {
  
  props: {
    toRender : {
      type: String,
      required : true
    }
  }


}
</script>

