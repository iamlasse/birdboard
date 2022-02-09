<script>
import { InertiaApp } from "@inertiajs/inertia-vue";
import Echo from "laravel-echo";
import Pusher from "pusher-js";

export default {
  name: "EchoApp",
  data() {
    return {
      echo: null,
    };
  },
  provide() {
    return {
      echo: this.echo,
    };
  },
  mounted() {
    console.log("Mounted App", this.$page);
  },
  watch: {
    currentUser: {
      handler(currentUser) {
        console.log("Current User", currentUser);
        currentUser !== null ? this.connect() : this.disconnect();
      },
      immediate: true,
    },
    isConnected: {
      handler(isConnected) {
        this.$emit("broadcasting:status", isConnected);
      },
    },
    updates: {
      handler(updates) {
        this.$emit("broadcasting:status", updates);
      },
    },
  },
  methods: {
    /** Connect Echo **/
    connect() {
      if (!this.echo) {
        this.echo = window.Echo || new Echo({
          broadcaster: "pusher",
          key: process.env.MIX_PUSHER_APP_KEY,
          cluster: process.env.MIX_PUSHER_APP_CLUSTER,
          forceTLS: true,
        });
        this.echo.connector.pusher.connection.bind("connected", (event) =>
          this.bindChannels(event)
        );
        this.echo.connector.pusher.connection.bind("disconnected", () =>
          this.disconnect()
        );
      }
      // this.echo.connector.pusher.config.auth.headers.Authorization = "Bearer " + this.currentUser.api_token;
      this.echo.connector.pusher.connect();
      console.log("Connected to Echo server", this.echo, window.Echo);
    },
    /** Bind Channels **/
    getEcho() {
      return this.echo;
    },
    bindChannels() {
      let vm = this;
      //   this.echo
      //     .private("App.Models.User" + "." + this.currentUser.id)
      //     .notification((object) => vm.$store.commit("addNotification", object));

      // this.echo.channel('project').listen('ProjectUpdated', (e) => {
      //       console.log('Got project update ', e.project);
      //       this.$inertia.reload({
      //         method: 'get',
      //         // data: {},
      //         // preserveState: false,
      //         preserveScroll: true,
      //         // only: [],
      //         // headers: {},
      //       })
      //       // livewire.emit('projectUpdated', e.project)
      //     });
      // this.echo
      //   .private("App.Models.User" + "." + this.currentUser.id)
      //   .notification((object) => vm.updates.push(object));
    },
    /** Disconnect Echo **/
    disconnect() {
      if (!this.echo) return;
      this.echo.disconnect();
    },
  },
  computed: {
    $page: {
      cache: false,
      get() {
        return this.$inertia.page?.props;
      },
    },
    currentUser: {
      cache: false,
      get() {
        return this.$page?.user;
      },
    },
    updates: {
      cache: false,
      get() {
        return this.$page?.updates.reverse();
      },
      set(update) {
        this.$page?.updates.push(update);
      },
    },
    isConnected: {
      cache: false,
      get() {
        return (
          this.echo && this.echo.connector.pusher.connection.connection !== null
        );
      },
    },
  },
  render: (h) =>
    h(InertiaApp, {
      props: {
        initialPage: JSON.parse(app.dataset.page),
        resolveComponent: (name) => require(`./Pages/${name}`).default,
      },
    }),
};
</script>