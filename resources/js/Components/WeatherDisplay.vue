<template>
    <div
        v-if="weather"
        :class="['p-6 rounded-xl shadow-2xl mx-auto max-w-2xl', weatherClass]"
    >
        <div class="flex flex-wrap items-center mb-6">
            <div
                class="flex-shrink-0 w-20 h-20 rounded-full bg-white bg-opacity-30 flex items-center justify-center mr-4 mb-2"
            >
                <i :class="weatherIcon" class="text-5xl text-white"></i>
            </div>
            <div class="flex-grow min-w-0">
                <h2 class="text-4xl font-bold text-white truncate">
                    {{ weather.municipio }}
                </h2>
                <div class="text-sm text-white text-opacity-80">
                    {{ formatDate() }}
                </div>
            </div>
        </div>
        <div class="text-center mb-8">
            <span class="text-7xl font-bold text-white block">
                {{ weather.temperatura_max }}°C
            </span>
            <span class="text-3xl text-white text-opacity-80">
                {{ weather.temperatura_min }}°C
            </span>
        </div>
        <div class="text-center text-white text-xl mb-6 font-medium">
            {{ weather.estado_cielo }}
        </div>
        <div class="grid grid-cols-4 gap-4 overflow-x-auto pb-2">
            <div
                v-for="(
                    probabilidad, periodo
                ) in weather.probabilidad_precipitacion"
                :key="periodo"
                class="bg-white bg-opacity-20 p-4 rounded-lg backdrop-blur-sm flex flex-col items-center justify-center"
            >
                <div class="w-12 h-12 mb-2 flex items-center justify-center">
                    <i
                        :class="getIcon(periodo, probabilidad)"
                        class="text-3xl text-white"
                    ></i>
                </div>
                <div class="text-white text-center">
                    <h4 class="text-sm font-semibold">
                        {{ formatPeriodo(periodo) }}
                    </h4>
                    <p class="text-lg font-bold">{{ probabilidad }}%</p>
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
            const baseClasses = "bg-gradient-to-br";
            if (this.weather.estado_cielo.toLowerCase().includes("lluvia")) {
                return `${baseClasses} from-blue-600 to-blue-900`;
            } else if (
                this.weather.estado_cielo.toLowerCase().includes("nub")
            ) {
                return `${baseClasses} from-gray-400 to-gray-700`;
            } else {
                return `${baseClasses} from-sky-400 to-indigo-600`;
            }
        },
        weatherIcon() {
            const iconMap = {
                despejado: "fas fa-sun",
                nuboso: "fas fa-cloud",
                lluvia: "fas fa-cloud-rain",
                "muy nuboso": "fas fa-cloud",
                cubierto: "fas fa-cloud",
                "nubes altas": "fas fa-cloud-sun",
                // Add more mappings as needed
            };
            return (
                iconMap[this.weather.estado_cielo.toLowerCase()] ||
                "fas fa-cloud"
            );
        },
    },
    methods: {
        formatDate() {
            const options = {
                weekday: "long",
                year: "numeric",
                month: "long",
                day: "numeric",
                timeZone: "Europe/Madrid",
            };
            return new Date().toLocaleDateString("es-ES", options);
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
        getIcon(periodo, probabilidad) {
            const hour = parseInt(periodo.split("-")[0]);
            const isDay = hour >= 6 && hour < 18;

            if (probabilidad === 0) {
                return isDay ? "fas fa-sun" : "fas fa-moon";
            } else if (probabilidad <= 20) {
                return isDay ? "fas fa-cloud-sun" : "fas fa-cloud-moon";
            } else if (probabilidad <= 50) {
                return isDay
                    ? "fas fa-cloud-sun-rain"
                    : "fas fa-cloud-moon-rain";
            } else {
                return "fas fa-cloud-showers-heavy";
            }
        },
    },
};
</script>

<style scoped>
@import url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css");

.grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
}

@media (max-width: 640px) {
    .grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

.backdrop-blur-sm {
    backdrop-filter: blur(4px);
}
</style>
