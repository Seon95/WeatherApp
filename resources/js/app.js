import "./bootstrap";
import "../css/app.css";
import { createApp } from "vue";
import WeatherComponent from "./components/WeatherComponent.vue";
import HeaderComponent from "./components/HeaderComponent.vue";

const app = createApp({});
app.component("weather-component", WeatherComponent);
app.component("header-component", HeaderComponent);

app.mount("#app");
