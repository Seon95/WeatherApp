// resources/js/app.js
import { createApp } from "vue";

// Importa el componente Vue
import MunicipioSelect from "./components/MunicipioSelect.vue";

const app = createApp({});

// Registra el componente
app.component("municipio-select", MunicipioSelect);

// Monta la aplicación en el div#app
app.mount("#app");
