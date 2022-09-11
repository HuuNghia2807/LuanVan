import { createApp } from "vue";
import App from "./App.vue";
import "./registerServiceWorker";
import router from "./router";
import store from "./store";
import PrimeVue from "primevue/config";
import BadgeDirective from "primevue/badgedirective";
import Dropdown from "primevue/dropdown";
import DataView from "primevue/dataview";
import Button from "primevue/button";
import Dialog from "primevue/dialog";
import InputText from "primevue/inputtext";

import "primevue/resources/themes/lara-light-blue/theme.css";
import "primevue/resources/primevue.min.css";
import "primeicons/primeicons.css";
import "@/assets/styles/global.css";

const app = createApp(App);

app.use(store);
app.use(router);
app.use(PrimeVue);

app.directive("badge", BadgeDirective);
app.component("DataView", DataView);
app.component("my-dropdown", Dropdown);
app.component("my-button", Button);
app.component("my-dialog", Dialog);
app.component("my-inputText", InputText);

app.mount("#app");
