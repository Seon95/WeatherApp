<template>
    <div>
        <label for="municipios">Selecciona un municipio:</label>
        <select v-model="selectedMunicipio" id="municipios">
            <option value="" disabled>Selecciona un municipio</option>
            <option
                v-for="municipio in municipios"
                :key="municipio.id"
                :value="municipio.id"
            >
                {{ municipio.id }} - {{ municipio.nombre }}
            </option>
        </select>
        <p v-if="selectedMunicipio">
            Municipio seleccionado: {{ getSelectedMunicipioNombre() }}
        </p>
    </div>
</template>

<script>
export default {
    data() {
        return {
            municipios: [],
            selectedMunicipio: "",
        };
    },
    created() {
        this.fetchMunicipios();
    },
    methods: {
        async fetchMunicipios() {
            try {
                const response = await fetch("/municipios");
                const data = await response.json();
                if (data.error) {
                    console.error(
                        "Error al obtener los municipios:",
                        data.error
                    );
                } else {
                    this.municipios = data;
                }
            } catch (error) {
                console.error("Error al obtener los municipios:", error);
            }
        },
        getSelectedMunicipioNombre() {
            const municipio = this.municipios.find(
                (m) => m.id === this.selectedMunicipio
            );
            return municipio ? municipio.nombre : "";
        },
    },
};
</script>

<style scoped>
select {
    padding: 8px;
    font-size: 16px;
    margin-top: 10px;
}

p {
    font-size: 16px;
    margin: 5px 0;
}
</style>
