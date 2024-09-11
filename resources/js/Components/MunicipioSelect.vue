<!-- MunicipioSelect.vue -->
<template>
    <div>
        <label for="municipios">Selecciona un municipio:</label>
        <select
            v-model="selectedMunicipioId"
            @change="$emit('municipio-selected', selectedMunicipioId)"
            id="municipios"
        >
            <option value="" disabled>Selecciona un municipio</option>
            <option
                v-for="municipio in municipios"
                :key="municipio.id"
                :value="municipio.id"
            >
                {{ municipio.id }} - {{ municipio.nombre }}
            </option>
        </select>
        <p v-if="selectedMunicipioId">
            Municipio seleccionado: {{ getSelectedMunicipioNombre() }}
        </p>
    </div>
</template>

<script>
export default {
    props: {
        municipios: Array,
        selectedMunicipio: String,
    },
    data() {
        return {
            selectedMunicipioId: this.selectedMunicipio,
        };
    },
    methods: {
        getSelectedMunicipioNombre() {
            const municipio = this.municipios.find(
                (m) => m.id === this.selectedMunicipioId
            );
            return municipio ? municipio.nombre : "";
        },
    },
};
</script>
