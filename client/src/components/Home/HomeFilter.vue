<template>
  <div class="filters">
    <div v-for="filter in filters" class="filter">
      <input type="radio" name="filter" :id="filter" :value="filter" @change="emitChange" v-model="filterChecked">
      <label :for="filter">{{ filter }}</label>
    </div>
  </div>
</template>

<script>

  export default {
    name: 'home-filter',
    data() {
      return {
        filters: ['Tout', 'Vidéos', 'Audio', 'Photos', 'Text', 'Rien'],
        filterChecked: 'Tout',
      };
    },
    methods: {
      emitChange() {
        let filter = [];
        switch (this.filterChecked) {
          case 'Photos':
            filter = ['instagram'];
            break;
          case 'Vidéos':
            filter = ['youtube'];
            break;
          case 'Audio':
            filter = ['soundcloud'];
            break;
          case 'Text':
            filter = ['facebook', 'tweet'];
            break;
          case 'Rien':
            filter = ['rien'];
            break;
          default:
            filter = ['facebook', 'tweet', 'soundcloud', 'youtube', 'instagram'];

        }
        this.$parent.$emit('changeFilter', filter);
      },
    },
  };
</script>

<style lang="scss" scoped>
  input{
    opacity: 0;
    width: 0;
    &:checked + label:before{
      width:120%;
    }
  }
  .filters{
    display: flex;
    justify-content: space-around;
    width: 80%;
    margin: 0 auto 20px auto;
  }
  label{
    font-family: 'times new roman', serif;
    font-weight: 600;
    position: relative;
    &:hover{
      cursor: pointer;
    }
    &:before{
      width:0;
      transition: all 0.4s ease;
      display: block;
      content: '';
      height: 2px;
      position: absolute;
      top:2px;
      bottom:2px;
      left:-10%;
      margin: auto;
      background-color: #000;
    }
  }
</style>
