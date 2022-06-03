<template>
  <q-page padding class="row">
    <div class="full-width column justify-center content-center items-center">
      <div>
        <q-form ref="phoneLoginForm">
          <q-input
            v-model="authFormStore.phoneNumber"
            label="Телефон"
          />
          <div class="row justify-center q-mt-md">
            <q-btn
              label="Войти"
              @click="phoneSubmitClicked"
              color="primary"
            />
          </div>
        </q-form>
      </div>
    </div>
  </q-page>
</template>

<script>
import {defineComponent, nextTick, ref} from "vue";
import {useAuth} from "stores/auth";
import {useAuthForm} from "stores/auth-form";
import {useRouter} from "vue-router";

export default defineComponent({
  name: 'LoginPhonePage',
  setup() {
    const authStore = useAuth();
    const authFormStore = useAuthForm();
    const phoneLoginForm = ref(null);
    const router = useRouter();

    const resetForm = () => {
      nextTick(function () {
        phoneLoginForm.value.resetValidation();
      })
    }

    nextTick(function () {
      resetForm();
    })

    const submitValidForm = () => {
      authStore.loginPhone(authFormStore.phoneNumber)
        .then(() => {
          resetForm();
          router.push({name: 'login-pincode'});
        });
    }

    const phoneSubmitClicked = () => {
      phoneLoginForm.value.validate().then(success => {
        if (!success) {
          return;
        }

        submitValidForm();
      })
    }

    return {
      authFormStore,
      phoneLoginForm,
      phoneSubmitClicked,
    }
  }
})
</script>
