<!-- WeatherComponent.vue -->
<template>
    <div class="max-w-2xl mx-auto p-6">
        <h1 class="text-3xl font-bold text-center text-gray-800 mb-8">
            Pronóstico del Tiempo
        </h1>
        <MunicipioSelect
            :municipios="municipios"
            :selectedMunicipio="selectedMunicipio"
            @municipio-selected="handleMunicipioSelected"
        />
        <WeatherDisplay v-if="weather" :weather="weather" />
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
