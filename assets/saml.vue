<script setup>
  import { ref } from 'vue';
  const samlSettings = ref({
    sp: { entityId: '', assertionConsumerService: { url: '' } },
    idp: { entityId: '', singleSignOnService: { url: '' }, x509cert: '' },
    debug: false,
    strict: false
  });

  function fetchSamlSettings() {
    fetch('/get-saml')
        .then(response => response.json())
        .then(data => {
          samlSettings.value = data;
        });
  }

  function updateSettings() {
    fetch('/set-saml', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(samlSettings.value)
    });
  }

  fetchSamlSettings();
</script>

<template>
  <v-container>
    <v-form @submit.prevent="updateSettings">
      <h3>SP Info</h3>
      <v-text-field
          label="SP Entity ID"
          v-model="samlSettings.sp.entityId"
          outlined
      ></v-text-field>

      <v-text-field
          label="ACS URL"
          v-model="samlSettings.sp.assertionConsumerService.url"
          outlined
      ></v-text-field>

      <h3>IdP Info</h3>
      <v-text-field
          label="SSO URL"
          v-model="samlSettings.idp.singleSignOnService.url"
          outlined
      ></v-text-field>

      <v-textarea
          label="x509 cert"
          v-model="samlSettings.idp.x509cert"
          outlined
          auto-grow
      ></v-textarea>

      <v-checkbox
          label="Debug"
          v-model="samlSettings.debug"
      ></v-checkbox>

      <v-checkbox
          label="Strict Mode"
          v-model="samlSettings.strict"
      ></v-checkbox>

      <v-btn color="primary" type="submit">Update Settings</v-btn>
    </v-form>
  </v-container>
</template>

<style scoped>

</style>