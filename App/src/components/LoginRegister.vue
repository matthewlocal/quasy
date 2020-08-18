<template>
  <q-form @submit="submitForm">
      <q-input
        outlined
         type="email"
         class="q-mb-md"
         v-model="formData.email"
         :rules="[val => !!val || 'Email is missing', isValidEmail]"
        label="Email" />
      <q-input
        outlined
        type="password"
        class="q-mb-md"
        v-model="formData.password"
        label="Password"
      />
      <div class="row">
        <q-space />
        <q-btn
        type="submit"
        color="primary"
        :label="tab" />
      </div>
  </q-form>

</template>
<script>
import { LocalStorage, SessionStorage } from 'quasar'
export default {
  props:['tab'],
  data(){
    return {
      formData: {
        email: '',
        password: '',
      }
    }
  },
  methods:{
    submitForm(e){

      e.preventDefault();

      if(this.tab == 'login'){

        this.$axios.post('https://gm.yard8.co.za/api/login', {
          email: this.formData.email,
          password: this.formData.password
        })
        .then(function (response) {

          if(response.data.status == true){
            SessionStorage.set('api_token', response.data.api_token)
            window.location.href = '#/mylist';
          }

        })
        .catch(function (error) {
          console.log(error)
        });
      }else{
        this.$axios.post('https://gm.yard8.co.za/api/register', {
          email: this.formData.email,
          password: this.formData.password
        })
        .then(function (response) {

          if(response.data.status == true){
              SessionStorage.set('api_token', response.data.api_token)
              window.location.href = '#/';
          }
        })
        .catch(function (error) {
          console.log(error)
        });
      }
    },
    isValidEmail (val) {
      const emailPattern = /^(?=[a-zA-Z0-9@._%+-]{6,254}$)[a-zA-Z0-9._%+-]{1,64}@(?:[a-zA-Z0-9-]{1,63}\.){1,8}[a-zA-Z]{2,63}$/;
      return emailPattern.test(val) || 'Invalid email';
    }
  },
}
</script>
