<style type="text/css">
body {
  background-color: #95c052;
}
</style>
<template>
  <div class="row">
    <div>
      <q-card class="login_card">
        <q-card-section>
          <div class="row justify-center items-center">
            <img
              src="../image/Logo.png"
              alt="Logo access energies"
              width="200"
              height="200"
            />
            <h3 class="dark_green_text">ACD Sign</h3>
          </div>
          <div class="row justify-center">
            <q-avatar
              size="80px"
              font-size="60px"
              color="white"
              text-color="black"
            >
              <span class="material-icons"> lock </span>
            </q-avatar>
          </div>
          <div class="row justify-center">
            <h4 class="dark_green_text">Connection</h4>
          </div>
          <div class="column items-center">
            <q-input
              outlined
              class="bg-white"
              v-model="text"
              style="min-width: 80%"
              label="Email"
            />
            <br />
            <q-input
              outlined
              class="bg-white"
              v-model="text"
              style="min-width: 80%"
              label="Mot de passe"
            />
            <br />
            <q-btn text-color="white" label="Se connecter" style="min-width: 50%; background-color: #94C24D;"/>
          </div>
        </q-card-section>
      </q-card>
    </div>
    <div>
      <img
        class="login-page-image"
        src="../image/Discussion-bro.png"
        alt="Logo access energies"
      />
    </div>
  </div>
</template>

<script>
import { defineComponent } from "vue";

export default defineComponent({
  name: "LoginPage",
});
</script>
