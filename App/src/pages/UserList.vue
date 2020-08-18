<template>
  <q-page class="bg-grey-3 column">
    <q-list class="bg-white" separator bordered>
      <q-item
      v-for="(list, index) in lists"
      :key="list.title"
      v-ripple>
        <q-item-section>
          <q-item-label>{{ list.title }}</q-item-label>
          <q-item-label caption lines="1">{{ list.description }}</q-item-label>
        </q-item-section>
      </q-item>
    </q-list>
    <div
    v-if="!lists.length"
    class="no-task absolute-center">
      <q-icon
      name="check"
      size="100px"
      color="primary" />
      <div class="text-h5 text-primary text-center">
        No List
      </div>
    </div>

  </q-page>
</template>

<script>
import { LocalStorage, SessionStorage } from 'quasar'

export default {
 data(){
    return{
      lists:[]
    }
  },
 created(){
      if(SessionStorage.has('api_token') == false){
        window.location.href = '#/';
      }else{
        this.$axios.post('https://gm.yard8.co.za/api/user/list',{
          'api_token': SessionStorage.getItem('api_token')
        }).then(Response => {

          if(Response.data.status != false){
            this.lists = Response.data.list
          }else{
            console.log(Response.data)
          }

      }).catch(error => {
        console.log(error)
      })
    }
  }
}
</script>

