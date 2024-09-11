<!-- WeatherComponent.vue -->
<template>
    <div>
        <MunicipioSelect
            :municipios="municipios"
            :selectedMunicipio="selectedMunicipio"
            @municipio-selected="handleMunicipioSelected"
        />
        <WeatherDisplay :weather="weather" />
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
            weather.value = await fetchTiempo(municipioId);
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
