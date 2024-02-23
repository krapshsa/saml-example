<script>
import TabComponent1 from './user.vue';
import TabComponent2 from './saml.vue';

export default {
  data: () => ({
    selectedItem: null,
    open: [],
    groups: {
      user: {
        title: 'User Setting',
        items: [{title: 'User Info', component: 'TabComponent1'}],
      },
      system: {
        title: 'System Setting',
        items: [{title: 'SAML', component: 'TabComponent2'}],
      },
    }
  }),
  computed: {
    activeComponent() {
      return this.selectedItem == null ? null : this.selectedItem.component;
    },
  },
  components: {
    // TabComponent1: () => import('./user.vue'),
    // TabComponent2: () => import('./saml.vue'),
    TabComponent1,
    TabComponent2
  },
};
</script>

<template>
  <v-app id="inspire">
    <v-app-bar dark>
      <v-app-bar-title>SAML Project</v-app-bar-title>
    </v-app-bar>

    <v-navigation-drawer>
      <v-container style="padding-right: 0;">
        <v-list :opened="open">
          <v-list-group
              v-for="(group, groupIndex) in groups"
              :key="groupIndex"
          >
            <template v-slot:activator="{ props }">
              <v-list-item
                  v-bind="props"
                  :title="group.title"
                  rounded="s"
              ></v-list-item>
            </template>

            <v-list-item
                v-for="(item, i) in group.items"
                :key="i"
                :title="item.title"
                :value="item"
                @click="selectedItem = item"
                rounded="s"
            ></v-list-item>
          </v-list-group>
        </v-list>
      </v-container>
    </v-navigation-drawer>

    <v-main>
      <v-container>
        <component :is="activeComponent"></component>
      </v-container>
    </v-main>
  </v-app>
</template>

<style scoped>

</style>
