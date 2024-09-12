<template>
    <div
        class="bg-gradient-to-br from-blue-50 to-blue-200 min-h-screen p-8 flex flex-col items-center"
    >
        <h1 class="text-5xl font-extrabold text-gray-800 mb-12 text-center">
            Pronóstico del Tiempo
        </h1>
        <div class="w-full max-w-4xl">
            <MunicipioSelect
                :municipios="municipios"
                :selectedMunicipio="selectedMunicipio"
                @municipio-selected="handleMunicipioSelected"
            />
            <WeatherDisplay v-if="weather" :weather="weather" />
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
/* Añadir estilos adicionales si es necesario */
</style>
