<template>
    <v-container class="d-flex justify-center align-center fill-height">
        <v-card class="pa-6" width="400" elevation="4">
            <v-card-title class="justify-center">
            <h3>Crear cuenta</h3>
            </v-card-title>

            <v-alert
                v-if="alert"
                :type="alert_type"
                border="start"
                elevation="2"
            >
                {{alert_msg}}
            </v-alert>
            
            <v-form @submit.prevent="handleRegister" ref="form" v-model="valid">
                <v-text-field
                    v-model="name"
                    label="Nombre completo"
                    prepend-inner-icon="mdi-account"
                    :rules="[rules.required]"
                    class="mb-4"
                />

                <v-text-field
                    v-model="email"
                    label="Correo electrónico"
                    type="email"
                    prepend-inner-icon="mdi-email"
                    :rules="[rules.required, rules.email]"
                    class="mb-4"
                />

                <v-text-field
                    v-model="password"
                    label="Contraseña"
                    type="password"
                    prepend-inner-icon="mdi-lock"
                    :rules="[rules.required, rules.min]"
                    class="mb-4"
                />

                <v-text-field
                    v-model="password_confirmation"
                    label="Confirmar contraseña"
                    type="password"
                    prepend-inner-icon="mdi-lock-check"
                    :rules="[rules.required, matchPassword]"
                    class="mb-4"
                />

                <v-btn type="submit" color="primary" block :loading="loading">
                    Registrarse
                </v-btn>
            </v-form>

            <v-card-actions class="justify-center mt-3">
            <v-btn text @click="goToLogin">¿Ya tienes cuenta? Inicia sesión</v-btn>
            </v-card-actions>
        </v-card>
    </v-container>
</template>

<script setup>
    import { ref } from 'vue'
    import { useRouter } from 'vue-router'
    import axios from 'axios'
    
    const name = ref('')
    const email = ref('')
    const password = ref('')
    const password_confirmation = ref('')
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
        min: v => v.length >= 6 || 'Mínimo 6 caracteres',
    }
    
    const matchPassword = () => {
        return password.value === password_confirmation.value || 'Las contraseñas no coinciden'
    }
    
    const handleRegister = async () => {
        if (!form.value.validate()) return
    
        loading.value = true
        alert.value =false
        alert_msg.value = ""
        alert_type.value = ""
        try {
        await axios.post('/api/register', {
            name: name.value,
            email: email.value,
            password: password.value,
            password_confirmation: password_confirmation.value,
        })
            alert.value =true
            alert_msg.value = "Registro exitoso, ahora puedes iniciar sesión."
            alert_type.value="success"
            router.push('/login')
        } catch (error) {
            alert.value =true
            alert_msg.value = "Error al registrar. Intenta con otro correo o revisa los campos."
            alert_type.value="error"
        } finally {
            loading.value = false
        }
    }
    
    const goToLogin = () => {
        router.push('/login')
    }
</script>