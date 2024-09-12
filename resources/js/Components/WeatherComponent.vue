$
<template>
    <div
        class="bg-gradient-to-br from-blue-200 to-indigo-500 min-h-screen py-12 px-4 sm:px-6 lg:px-8 flex flex-col items-center"
    >
        <div class="w-full max-w-4xl">
            <h1
                class="text-5xl font-extrabold text-white mb-12 text-center tracking-tight"
            >
                Pronóstico del Tiempo
            </h1>
            <div
                class="bg-white bg-opacity-20 backdrop-blur-lg rounded-3xl shadow-2xl p-8 mb-8"
            >
                <MunicipioSelect
                    :municipios="municipios"
                    :selectedMunicipio="selectedMunicipio"
                    @municipio-selected="handleMunicipioSelected"
                />
                <transition name="fade" mode="out-in">
                    <WeatherDisplay v-if="weather" :weather="weather" />
                    <div v-else class="text-center text-white text-xl mt-8">
                        Selecciona un municipio para ver el pronóstico
                    </div>
                </transition>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, onMounted } from "vue";
import MunicipioSelect from "./MunicipioSelect.vue";
import WeatherDisplay from "./WeatherDisplay.vue";
import { fetchMunicipios, fetchTiempo } from "../services/api";

export default {
    components: {
        MunicipioSelect,
        WeatherDisplay,
    },
    setup() {
        const municipios = ref([]);
        const selectedMunicipio = ref("");
        const weather = ref(null);

        onMounted(async () => {
            municipios.value = await fetchMunicipios();
        });

        const handleMunicipioSelected = async (municipioId) => {
            selectedMunicipio.value = municipioId;
            weather.value = null; // Reset weather while loading
            const weatherData = await fetchTiempo(municipioId);
            if (weatherData) {
                weather.value = {
                    ...weatherData,
                    municipio:
                        municipios.value.find((m) => m.id === municipioId)
                            ?.nombre || "",
                };
            }
        };

        return {
            municipios,
            selectedMunicipio,
            weather,
            handleMunicipioSelected,
        };
    },
};
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
$
