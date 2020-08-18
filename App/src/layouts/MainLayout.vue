<template>
  <q-layout view="lHh Lpr lFf">
    <q-header elevated>
      <q-toolbar>
        <q-toolbar-title>
         {{ title }}
         <q-btn
          @click="logout"
          round
          dense
          flat
          icon="logout" />
        </q-toolbar-title>
        <div>Api Version: {{ apiVersion }}</div>
      </q-toolbar>
    </q-header>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>
import { LocalStorage, SessionStorage } from 'quasar'
export default {
  name: 'MainLayout',
  computed:{
    title(){
      let currentURI = this.$route.fullPath
      if(currentURI == '/') return 'Go Media Login'
      else if(currentURI == '/mylist') return 'Go Media Your List'
    }
  },
  created(){
      this.$axios.get('https://gm.yard8.co.za/api').then(Response => {
        //Get API version
        this.apiVersion = Response.data.version

     }).catch(error => {
      console.log(error)
    })
  },
  data () {
    return {
      apiVersion: ''
    }
  },
  methods:{
      logout(){
        SessionStorage.clear('api_token')
        window.location.href = '#/';
    }
  }
}
</script>
