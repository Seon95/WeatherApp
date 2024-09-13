const API_KEY = import.meta.env.VITE_AEMET_API_KEY;

export async function fetchMunicipios() {
    try {
        const response = await fetch("/municipios");
        return await response.json();
    } catch (error) {
        console.error("Error al obtener los municipios:", error);
        return [];
    }
}

export async function fetchTiempo(municipioId) {
    try {
        const response = await fetch(
            `https://opendata.aemet.es/opendata/api/prediccion/especifica/municipio/diaria/${municipioId}?api_key=${API_KEY}`
        );
        const data = await response.json();

        if (data.datos) {
            const tiempoResponse = await fetch(data.datos);
            const tiempoData = await tiempoResponse.json();
            console.log(tiempoData);

            if (tiempoData && tiempoData.length > 0) {
                const prediccion = tiempoData[0].prediccion.dia[0];

                const probPrecipitacionPorPeriodo = {
                    "00-06":
                        prediccion.probPrecipitacion.find(
                            (periodo) => periodo.periodo === "00-06"
                        )?.value || 0,
                    "06-12":
                        prediccion.probPrecipitacion.find(
                            (periodo) => periodo.periodo === "06-12"
                        )?.value || 0,
                    "12-18":
                        prediccion.probPrecipitacion.find(
                            (periodo) => periodo.periodo === "12-18"
                        )?.value || 0,
                    "18-24":
                        prediccion.probPrecipitacion.find(
                            (periodo) => periodo.periodo === "18-24"
                        )?.value || 0,
                };

                return {
                    fecha: prediccion.fecha,
                    temperatura_min: prediccion.temperatura.minima,
                    temperatura_max: prediccion.temperatura.maxima,
                    estado_cielo:
                        prediccion.estadoCielo.find(
                            (periodo) => periodo.periodo === "00-24"
                        )?.descripcion || "",
                    probabilidad_precipitacion: probPrecipitacionPorPeriodo,
                };
            }
        }
        throw new Error("No se pudo obtener la predicción del tiempo.");
    } catch (error) {
        console.error("Error al obtener el tiempo:", error);
        return null;
    }
}
