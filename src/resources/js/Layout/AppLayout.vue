<template>
    <v-app>
        <!-- Barra de navegación -->
        <v-app-bar app color="primary" dark>
            <v-toolbar-title class="me-4">Mi Tienda</v-toolbar-title>
            <v-spacer></v-spacer>

            <v-btn variant="text" to="/" class="text-white">Productos</v-btn>

            <!-- Si NO está logueado -->
            <template v-if="!isLoggedIn">
            <v-btn variant="text" to="/login" class="text-white">Iniciar sesión</v-btn>
            <v-btn variant="text" to="/register" class="text-white">Registrarse</v-btn>
            </template>

            <!-- Si SÍ está logueado -->
            <template v-else>
            <v-btn variant="text" to="/orders" class="text-white">Órdenes</v-btn>
            <v-btn variant="text" class="text-white" disabled>
                Bienvenido, {{ user?.name || 'Usuario' }}
            </v-btn>
            <v-btn variant="text" @click="logout" class="text-white">Cerrar sesión</v-btn>
            </template>
        </v-app-bar>

        <!-- Contenido -->
        <v-main class="pa-4">
            <router-view />
        </v-main>
    </v-app>
</template>

<script setup>
    import { ref, watchEffect } from 'vue'
    import { useRouter } from 'vue-router'
    
    import { getUser, isAuthenticated, logoutUser } from '../utils/auth'

    const router = useRouter()
    const isLoggedIn = ref(isAuthenticated())
    const user = ref(getUser())

    watchEffect(() => {
        isLoggedIn.value = isAuthenticated()
        //console.log(isLoggedIn.value)
        user.value = getUser()
        //console.log(user.value)
    })

    const logout = () => {
        logoutUser()
        isLoggedIn.value = false
        user.value = null
        router.push('/login')
    }
</script>