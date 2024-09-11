<!-- WeatherDisplay.vue -->
<template>
    <div v-if="weather">
        <h2>Predicción meteorológica:</h2>
        <p>Fecha: {{ weather.fecha }}</p>
        <p>Temperatura mínima: {{ weather.temperatura_min }}°C</p>
        <p>Temperatura máxima: {{ weather.temperatura_max }}°C</p>
        <p>Descripción: {{ weather.estado_cielo }}</p>
    </div>
</template>

<script>
export default {
    props: {
        weather: Object,
    },
};
</script>
