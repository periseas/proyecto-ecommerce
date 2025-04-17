<template> 
    <v-container class="d-flex justify-center align-center fill-height">
        <v-card class="pa-6" width="400" elevation="4">
            <v-card-title class="justify-center">
                <h3>Iniciar sesión</h3>
            </v-card-title>
            
            <v-alert
                v-if="alert"
                :type="alert_type"
                border="start"
                elevation="2"
            >
                {{alert_msg}}
            </v-alert>

            <v-form @submit.prevent="handleLogin" ref="form" v-model="valid">
                <v-text-field
                    v-model="email"
                    label="Correo electrónico"
                    type="email"
                    :rules="[rules.required, rules.email]"
                    prepend-inner-icon="mdi-email"
                    class="mb-4"
                ></v-text-field>
                
                <v-text-field
                    v-model="password"
                    label="Contraseña"
                    type="password"
                    :rules="[rules.required]"
                    prepend-inner-icon="mdi-lock"
                    class="mb-4"
                ></v-text-field>
                
                <v-btn type="submit" color="primary" block :loading="loading">
                    Iniciar sesión
                </v-btn>
            </v-form>
            
            <v-card-actions class="justify-center mt-3">
                <v-btn text @click="goToRegister">¿No tienes cuenta? Regístrate</v-btn>
            </v-card-actions>
        </v-card>
    </v-container>
</template>

<script setup>
    import { ref } from 'vue'
    import { useRouter } from 'vue-router'
    import axios from 'axios'
    
    const email = ref('')
    const password = ref('')
    const loading = ref(false)
    const valid = ref(true)
    const form = ref(null)
    const alert = ref(false)
    const alert_msg = ref("")
    const alert_type = ref("")
    
    const router = useRouter()
    
    const rules = {
        required: v => !!v || 'Este campo es requerido',
        email: v => /.+@.+\..+/.test(v) || 'Correo inválido',
    }
    
    const handleLogin = async () => {
        if (!form.value.validate()) return
    
        loading.value = true
        alert.value =false
        alert_msg.value = ""
        alert_type.value = ""
        try {
        const response = await axios.post('/api/login', {
            email: email.value,
            password: password.value,
        })
        if (response.data.token) {
            alert.value =true
            alert_msg.value = "Login Correcto"
            alert_type.value="success"
            localStorage.setItem('token', response.data.token)
            localStorage.setItem('user', JSON.stringify(response.data.user))
            router.push('/').then(() => {
                window.location.reload() 
            })
        }
        } catch (error) {
            alert.value =true
            alert_msg.value = "Credenciales incorrectas"
            alert_type.value="error"
        } finally {
        loading.value = false
        }
    }
    
    const goToRegister = () => {
        router.push('/register')
    }
</script>