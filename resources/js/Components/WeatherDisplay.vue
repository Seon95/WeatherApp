<template>
    <div
        v-if="weather"
        :class="[
            'p-6 rounded-lg shadow-xl max-w-lg mx-auto bg-gradient-to-br from-white to-blue-100',
            weatherClass,
        ]"
    >
        <div class="flex items-center mb-4">
            <div
                class="flex-shrink-0 w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center"
            >
                <i :class="weatherIcon" class="text-4xl text-gray-800"></i>
            </div>
            <div class="ml-4">
                <h2 class="text-4xl font-bold text-gray-800">
                    {{ weather.municipio }}
                </h2>
                <div class="text-sm text-gray-600">
                    {{ formatDate(weather.fecha) }}
                </div>
            </div>
        </div>
        <div class="text-center mb-6">
            <span class="text-6xl font-bold text-gray-800 block">
                {{ weather.temperatura_max }}°C
            </span>
            <span class="text-2xl text-gray-600">
                {{ weather.temperatura_min }}°C
            </span>
        </div>
        <div class="text-center text-gray-800 text-lg">
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
                ? "bg-gradient-to-br from-blue-300 to-blue-500"
                : "bg-gradient-to-br from-yellow-200 to-yellow-400";
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

<style scoped>
/* Añadir estilos adicionales si es necesario */
</style>
