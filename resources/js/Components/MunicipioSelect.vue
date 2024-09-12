$
<template>
    <div class="mb-8 w-full max-w-2xl mx-auto">
        <label
            for="municipios"
            class="block text-xl font-semibold text-gray-700 mb-3"
        >
            Selecciona un municipio:
        </label>
        <div class="relative">
            <select
                v-model="selectedMunicipioId"
                @change="$emit('municipio-selected', selectedMunicipioId)"
                id="municipios"
                class="block w-full pl-4 pr-10 py-3 text-lg border-2 border-blue-300 bg-white text-gray-700 rounded-xl shadow-lg appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
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
            <div
                class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700"
            >
                <svg
                    class="fill-current h-6 w-6"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20"
                >
                    <path
                        d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"
                    />
                </svg>
            </div>
        </div>
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
.shadow-lg {
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1),
        0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

select {
    -webkit-appearance: none;
    -moz-appearance: none;
    text-indent: 1px;
    text-overflow: "";
}

select::-ms-expand {
    display: none;
}
</style>
