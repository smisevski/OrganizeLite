<template>
  <v-navigation-drawer
    v-model="drawer"
    relative
    permanent
    stateless
    class="blue darken-3"
  >
    <v-toolbar dark flat class="transparent">
      <img src="images/logoOrganiseLite_1.png" width="170" height="50">
    </v-toolbar>

    <v-toolbar dark flat class="transparent">
      <v-list>
        <v-list-tile 
        avatar
        class="pl-2"
        @click="a"
        >
          <v-list-tile-avatar>
            <img src="https://www.gannett-cdn.com/-mm-/96e6ed023349db3262ad18c41bfd710f5ae847a1/c=0-17-1974-1501/local/-/media/2017/08/15/PAGroup/YorkDailyRecord/636383949277161459-GettyImages-493437460.jpg?width=534&height=401&fit=crop">
          </v-list-tile-avatar>

          <v-list-tile-content>
            <v-list-tile-title>Sasho Mishevski</v-list-tile-title>
          </v-list-tile-content>

        </v-list-tile>
      </v-list>
    </v-toolbar>

    <v-list class="pl-1" dense>
      <v-divider></v-divider>

      <v-list-tile
        v-for="item in items"
        :key="item.title"
        color="white"
        @click="OpenPanel(item.panel)"
        active-class="darken-2"
      >
        <v-list-tile-action>
          <v-icon color="white">{{ item.icon }}</v-icon>
        </v-list-tile-action>

        <v-list-tile-content>
          <v-list-tile-title>{{ item.title }}</v-list-tile-title>
        </v-list-tile-content>
      </v-list-tile>
      <v-divider></v-divider>
    </v-list>
  </v-navigation-drawer>
</template>

<script>
  export default {
    name: "navigation-drawer",
    props: ["drawerWidth"],
    data () {
      return {
        calculatedDrawerWidth: 0,
        drawer: true,
        items: [
          { title: 'Dashboard', icon: 'dashboard' },
          { title: 'Clients', icon: 'group', panel: 'clients' },
          { title: 'Products', icon: 'label', panel: 'products' },
          { title: 'Invoices', icon: 'receipt', panel: 'invoices' },
          { title: 'Projects', icon: 'markunread_mailbox' },
          { title: 'Settings', icon: 'settings' },
        ],
        mini: true,
        right: null
      }
    },
    watch: {
      drawerWidth: function(newVal) {
        this.calculatedDrawerWidth = newVal;
      }
    },
    methods: {
      OpenPanel(panelName) {
        this.$emit('open-panel', panelName);
      },
      a(){}
    },
    mounted() {
      this.calculatedDrawerWidth = this.drawerWidth;
    }
  }
</script>