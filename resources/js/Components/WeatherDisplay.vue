<!-- WeatherDisplay.vue -->
<template>
    <div
        v-if="weather"
        :class="['p-6 rounded-lg shadow-lg max-w-sm mx-auto', weatherClass]"
    >
        <h2 class="text-2xl font-bold text-white mb-2">
            {{ weather.municipio }}
        </h2>
        <div class="text-sm text-gray-200 mb-4">
            {{ formatDate(weather.fecha) }}
        </div>
        <div class="flex justify-between items-center mb-4">
            <div class="text-5xl text-white">
                <i :class="weatherIcon"></i>
            </div>
            <div class="text-right">
                <span class="text-4xl font-bold text-white block"
                    >{{ weather.temperatura_max }}°C</span
                >
                <span class="text-xl text-gray-200"
                    >{{ weather.temperatura_min }}°C</span
                >
            </div>
        </div>
        <div class="text-center text-white text-lg">
            {{ weather.estado_cielo }}
        </div>
    </div>
</template>

<script>
export default {
    props: {
        weather: Object,
    },
    computed: {
        weatherClass() {
            return this.weather.estado_cielo.toLowerCase().includes("lluvia")
                ? "bg-gradient-to-br from-blue-500 to-blue-700"
                : "bg-gradient-to-br from-yellow-400 to-orange-500";
        },
        weatherIcon() {
            const iconMap = {
                despejado: "fas fa-sun",
                nuboso: "fas fa-cloud",
                lluvia: "fas fa-cloud-rain",
                // Añadir más mapeos según sea necesario
            };
            return (
                iconMap[this.weather.estado_cielo.toLowerCase()] ||
                "fas fa-cloud"
            );
        },
    },
    methods: {
        formatDate(dateString) {
            const options = {
                weekday: "long",
                year: "numeric",
                month: "long",
                day: "numeric",
            };
            return new Date(dateString).toLocaleDateString("es-ES", options);
        },
    },
};
</script>
