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
        const response = await fetch(`/tiempo/${municipioId}`);
        if (!response.ok) {
            throw new Error("Error en la respuesta del servidor");
        }
        const data = await response.json();

        if (data.error) {
            throw new Error(data.error);
        }

        return {
            fecha: data.fecha,
            temperatura_min: data.temperatura_min,
            temperatura_max: data.temperatura_max,
            estado_cielo: data.estado_cielo,
            probabilidad_precipitacion: data.probabilidad_precipitacion,
        };
    } catch (error) {
        console.error("Error al obtener el tiempo:", error);
        return null;
    }
}
