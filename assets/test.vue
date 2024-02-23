<script setup>
const items = [
  {
    name: 'African Elephant',
    species: 'Loxodonta africana',
    diet: 'Herbivore',
    habitat: 'Savanna, Forests',
  },
]
</script>

<template>
  <v-data-table :items="items"></v-data-table>
</template>

