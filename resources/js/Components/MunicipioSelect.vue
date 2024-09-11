<!-- MunicipioSelect.vue -->
<template>
    <div class="mb-6">
        <label
            for="municipios"
            class="block text-sm font-medium text-gray-700 mb-2"
        >
            Selecciona un municipio:
        </label>
        <select
            v-model="selectedMunicipioId"
            @change="$emit('municipio-selected', selectedMunicipioId)"
            id="municipios"
            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
        >
            <option value="" disabled>Selecciona un municipio</option>
            <option
                v-for="municipio in municipios"
                :key="municipio.id"
                :value="municipio.id"
            >
                {{ municipio.nombre }}
            </option>
        </select>
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
};
</script>
