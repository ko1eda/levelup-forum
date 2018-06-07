<template>

  <div>
    <div v-show="isFavorited">
      <button class="tw-flex tw-items-center" type="submit" @click="handleFavoriteToggle">
        <i class="fas fa-star tw-text-xs tw-text-yellow-dark"></i>
        <span class="tw-ml-1 tw-text-xs sm:tw-text-sm tw-text-bulma-dark"> {{ favoriteCount }} </span>
      </button>
    </div>
    <div v-show="!isFavorited">
      <button type="submit" class="tw-flex tw-items-center" @click="handleFavoriteToggle">
        <i class="far fa-star tw-text-xs tw-text-bulma-dark hover:tw-text-yellow-dark"></i>
        <span class="tw-ml-1 tw-text-xs sm:tw-text-sm tw-text-bulma-dark"> {{ favoriteCount }} </span>
      </button>
    </div>
  </div>

</template>


<script>
export default {
  
  props: {
    replyData: {
      type: Object,
      required: true
    }
  },

  data() {
    return {
      isFavorited: this.replyData.is_favorited,
      favoriteCount: this.replyData.favorites_count,
      endpoint: `/replies/${this.replyData.id}/favorites`
    }
  },

  methods: {
    // if the user has favorited the reply
    // then unfavorite it and decrement the reply counter by 1
    // else favorite the reply and increment the counter by 1 
    handleFavoriteToggle() {
      if(this.isFavorited){
        axios.delete(this.endpoint)
          .then(res => {
            this.favoriteCount--;
            this.isFavorited = false;
            
          });
      }
      else {
        axios.post(this.endpoint)
          .then(res => {
             this.favoriteCount++;
             this.isFavorited = true;
          });
      }
      
    }
  },
  
  computed: {
    // had issues with font-awesome not 
    // re-rendering check 
    // https://github.com/FortAwesome/vue-fontawesome/issues/30
    iconClass() {
      return [
        'tw-text-xs',
        {'fas fa-star tw-text-yellow-dark' : this.isFavorited},
        {'far fa-star tw-text-bulma-dark hover:tw-text-yellow-dark': !this.isFavorited}
      ];
    }
  }

}
</script>
