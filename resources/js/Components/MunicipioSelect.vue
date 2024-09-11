<template>
    <div>
        <label for="municipios">Selecciona un municipio:</label>
        <select
            v-model="selectedMunicipio"
            @change="fetchTiempo(selectedMunicipio)"
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
        <p v-if="selectedMunicipio">
            Municipio seleccionado: {{ getSelectedMunicipioNombre() }}
        </p>

        <!-- Mostrar información del tiempo -->
        <div v-if="tiempo">
            <h2>Predicción meteorológica:</h2>
            <p>Fecha: {{ tiempo.fecha }}</p>
            <p>Temperatura mínima: {{ tiempo.temperatura_min }}°C</p>
            <p>Temperatura máxima: {{ tiempo.temperatura_max }}°C</p>
            <p>Descripción: {{ tiempo.estado_cielo }}</p>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            municipios: [],
            selectedMunicipio: "",
            tiempo: null, // Para almacenar los datos meteorológicos
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
                this.municipios = data;
            } catch (error) {
                console.error("Error al obtener los municipios:", error);
            }
        },
        async fetchTiempo(municipioId) {
            try {
                const response = await fetch(
                    `https://opendata.aemet.es/opendata/api/prediccion/especifica/municipio/diaria/${municipioId}?api_key=eyJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJtYXJvdWFuXzM5QGhvdG1haWwuY29tIiwianRpIjoiNjAxYmM3ZGYtY2I5NS00M2U5LTk2MmYtNGU5NGQxMWY0NTQ2IiwiaXNzIjoiQUVNRVQiLCJpYXQiOjE3MjU4MjEwNDksInVzZXJJZCI6IjYwMWJjN2RmLWNiOTUtNDNlOS05NjJmLTRlOTRkMTFmNDU0NiIsInJvbGUiOiIifQ.41pKSJ6k0kNCZN3JQptRs-GA1U4sQkf0npwyifVfShc`
                );
                const data = await response.json();

                if (data.datos) {
                    // Hacer una segunda petición para obtener los datos reales del clima
                    const tiempoResponse = await fetch(data.datos);
                    const tiempoData = await tiempoResponse.json();

                    if (tiempoData && tiempoData.length > 0) {
                        // Utilizar el primer objeto del array para la predicción del día actual
                        const prediccion = tiempoData[0].prediccion.dia[0];
                        this.tiempo = {
                            fecha: prediccion.fecha,
                            temperatura_min: prediccion.temperatura.minima,
                            temperatura_max: prediccion.temperatura.maxima,
                            estado_cielo:
                                prediccion.estadoCielo.find(
                                    (periodo) => periodo.periodo === "00-24"
                                )?.descripcion || "No disponible",
                        };
                    }
                } else {
                    throw new Error("No se pudo obtener la URL de predicción.");
                }
            } catch (error) {
                console.error("Error al obtener el tiempo:", error);
                this.tiempo = null; // Limpiar si hay un error
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

h2 {
    margin-top: 20px;
    font-size: 20px;
}

p {
    font-size: 16px;
    margin: 5px 0;
}
</style>
