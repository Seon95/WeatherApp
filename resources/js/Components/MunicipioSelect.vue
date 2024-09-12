<template>
    <div class="mb-6 w-full max-w-4xl mx-auto">
        <label
            for="municipios"
            class="block text-lg font-medium text-gray-800 mb-2"
        >
            Selecciona un municipio:
        </label>
        <select
            v-model="selectedMunicipioId"
            @change="$emit('municipio-selected', selectedMunicipioId)"
            id="municipios"
            class="mt-1 block w-full pl-3 pr-10 py-3 text-base border border-gray-300 bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-lg rounded-lg shadow-md"
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

<style scoped>
/* Añadir estilos adicionales si es necesario */
</style>
