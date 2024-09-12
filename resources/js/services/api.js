// api.js
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
        const data = await response.json();
        return data;
    } catch (error) {
        console.error("Error al obtener el tiempo:", error);
        return null;
    }
}
