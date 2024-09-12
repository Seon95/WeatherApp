<template>
    <header
        class="bg-gradient-to-r from-blue-800 to-blue-400 text-white py-4 shadow-md"
    >
        <div class="container mx-auto flex justify-between items-center">
            <!-- Logo -->
            <div class="flex items-center">
                <img
                    src="/public/tiempo.png"
                    alt="Logo"
                    class="w-16 h-16 mr-3"
                />
                <h1 class="text-2xl font-bold">Mi App del Tiempo</h1>
            </div>

            <!-- Navigation Links -->
            <nav>
                <ul class="flex space-x-6">
                    <li>
                        <a href="#" class="hover:underline">Inicio</a>
                    </li>

                    <li>
                        <a href="#" class="hover:underline">Contacto</a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>
</template>

<script>
export default {
    name: "HeaderComponent",
};
</script>

<style scoped>
/* Estilos adicionales si los necesitas */
</style>
