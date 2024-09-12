<template>
    <div
        v-if="weather"
        :class="[
            'p-6 rounded-lg shadow-xl mx-auto bg-gradient-to-br from-white to-purple-100',
            weatherClass,
        ]"
    >
        <div class="flex flex-wrap items-center mb-4">
            <div
                class="flex-shrink-0 w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center mr-4 mb-2"
            >
                <i :class="weatherIcon" class="text-4xl text-gray-800"></i>
            </div>
            <div class="flex-grow min-w-0">
                <h2 class="text-4xl font-bold text-gray-800 truncate">
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
        <div class="flex justify-between overflow-x-auto pb-2">
            <div
                v-for="(
                    probabilidad, periodo
                ) in weather.probabilidad_precipitacion"
                :key="periodo"
                class="bg-white p-4 rounded-lg shadow-md flex flex-col items-center flex-shrink-0 w-1/4 min-w-[120px] mx-1"
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
                ? "bg-gradient-to-br from-purple-400 to-orange-500"
                : "bg-gradient-to-br from-pink-200 to-yellow-300";
        },
        weatherIcon() {
            const iconMap = {
                despejado: "fas fa-sun",
                nuboso: "fas fa-cloud",
                lluvia: "fas fa-cloud-rain",
                // Add more mappings as needed
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
.flex {
    display: flex;
    flex-wrap: nowrap;
}

.overflow-x-auto {
    overflow-x: auto;
    scrollbar-width: thin;
    scrollbar-color: rgba(156, 163, 175, 0.5) transparent;
}

.overflow-x-auto::-webkit-scrollbar {
    height: 6px;
}

.overflow-x-auto::-webkit-scrollbar-track {
    background: transparent;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
    background-color: rgba(156, 163, 175, 0.5);
    border-radius: 20px;
}

@media (max-width: 640px) {
    .flex > div {
        min-width: 100px;
    }
}
</style>
