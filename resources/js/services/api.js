// api.js
const API_KEY =
    "eyJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJtYXJvdWFuXzM5QGhvdG1haWwuY29tIiwianRpIjoiNjAxYmM3ZGYtY2I5NS00M2U5LTk2MmYtNGU5NGQxMWY0NTQ2IiwiaXNzIjoiQUVNRVQiLCJpYXQiOjE3MjU4MjEwNDksInVzZXJJZCI6IjYwMWJjN2RmLWNiOTUtNDNlOS05NjJmLTRlOTRkMTFmNDU0NiIsInJvbGUiOiIifQ.41pKSJ6k0kNCZN3JQptRs-GA1U4sQkf0npwyifVfShc";

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

            if (tiempoData && tiempoData.length > 0) {
                const prediccion = tiempoData[0].prediccion.dia[0];
                return {
                    fecha: prediccion.fecha,
                    temperatura_min: prediccion.temperatura.minima,
                    temperatura_max: prediccion.temperatura.maxima,
                    estado_cielo:
                        prediccion.estadoCielo.find(
                            (periodo) => periodo.periodo === "00-24"
                        )?.descripcion || "No disponible",
                };
            }
        }
        throw new Error("No se pudo obtener la predicción del tiempo.");
    } catch (error) {
        console.error("Error al obtener el tiempo:", error);
        return null;
    }
}