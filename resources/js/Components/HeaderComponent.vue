<template>
    <header
        :class="['py-4 shadow-md transition-colors duration-300', weatherClass]"
    >
        <div class="container mx-auto flex justify-between items-center px-4">
            <!-- Logo -->
            <div class="flex items-center">
                <img
                    src="/public/tiempo.png"
                    alt="Logo"
                    class="w-12 h-12 mr-3"
                />
                <h1 class="text-2xl font-bold text-white">El Tiempo</h1>
            </div>

            <!-- Navigation Links -->
            <nav>
                <ul class="flex space-x-6">
                    <li>
                        <a
                            href="#"
                            class="text-white hover:text-opacity-80 transition-colors duration-200"
                            >Inicio</a
                        >
                    </li>
                    <li>
                        <a
                            href="#"
                            class="text-white hover:text-opacity-80 transition-colors duration-200"
                            >Contacto</a
                        >
                    </li>
                    <li>
                        <a
                            href="/municipios"
                            target="_blank"
                            class="text-white hover:text-opacity-80 transition-colors duration-200"
                            >API Municipios</a
                        >
                    </li>
                </ul>
            </nav>
        </div>
    </header>
</template>

<script>
export default {
    name: "HeaderComponent",
    props: {
        weather: Object,
    },
    computed: {
        weatherClass() {
            const baseClasses = "bg-gradient-to-r";
            if (this.weather?.estado_cielo.toLowerCase().includes("lluvia")) {
                return `${baseClasses} from-blue-600 to-blue-800`;
            } else if (
                this.weather?.estado_cielo.toLowerCase().includes("nub")
            ) {
                return `${baseClasses} from-gray-500 to-gray-700`;
            } else {
                return `${baseClasses} from-sky-400 to-indigo-600`;
            }
        },
    },
};
</script>

<style scoped></style>
