<template>
    <div
        v-if="weather"
        :class="[
            'p-6 rounded-lg shadow-xl mx-auto bg-gradient-to-br from-white to-blue-100',
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
        <div class="text-center text-gray-800 text-lg mb-4">
            {{ weather.estado_cielo }}
        </div>
        <div class="flex justify-between mb-4">
            <div
                v-for="(
                    probabilidad, periodo
                ) in weather.probabilidad_precipitacion"
                :key="periodo"
                class="bg-white p-4 rounded-lg shadow-md flex flex-col items-center flex-1 mx-1"
            >
                <div class="w-12 h-12 mb-2 flex items-center justify-center">
                    <i :class="getIcon(periodo)" class="text-3xl"></i>
                </div>
                <div class="text-gray-800 text-center">
                    <h4 class="text-lg font-semibold">
                        {{ formatPeriodo(periodo) }}
                    </h4>
                    <p class="text-xl font-bold">{{ probabilidad }}%</p>
                </div>
            </div>
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
        formatPeriodo(periodo) {
            const periods = {
                "00-06": "00h - 06h",
                "06-12": "06h - 12h",
                "12-18": "12h - 18h",
                "18-24": "18h - 24h",
            };
            return periods[periodo] || periodo;
        },
        getIcon(periodo) {
            const iconMap = {
                "00-06": "fas fa-cloud-moon",
                "06-12": "fas fa-cloud-sun",
                "12-18": "fas fa-cloud-sun-rain",
                "18-24": "fas fa-cloud-moon-rain",
            };
            return iconMap[periodo] || "fas fa-cloud";
        },
    },
};
</script>

<style scoped>
/* Asegúrate de que el contenedor flex ocupe el ancho completo y ajuste los elementos */
.flex {
    display: flex;
    flex-wrap: nowrap; /* Asegúrate de que los elementos no se envuelvan */
    overflow-x: auto; /* Agrega scroll horizontal si es necesario */
}

.flex > div {
    flex: 1 1 auto; /* Permite que los elementos se estiren */
    max-width: 150px; /* Ajusta el ancho máximo si es necesario */
}
</style>
