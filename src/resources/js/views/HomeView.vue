<template>
    <v-container>
        <!-- Banner -->
        <v-img
            height="280px"
            class="rounded-xl mb-4"
            cover
        >
            <div
                class="d-flex flex-column justify-center align-center h-100 p-6 text-white"
                style="background: rgba(0,0,0,0.4); text-align: center;"
            >
                <h1 class="text-h4 font-weight-bold">{{ title }}</h1>
                <v-btn color="primary" class="mt-3" @click="goToLogin">Iniciar sesión</v-btn>
            </div>
        </v-img>


        <!-- Contador de carrito -->
        <v-btn icon color="primary" class="mb-6" @click="showCart = true">
            <v-badge :content="cart?.length || 0" color="red" overlap>
                <v-icon>mdi-cart</v-icon>
            </v-badge>
        </v-btn>

        <!-- Lista de productos -->
        <v-row>
            <v-col
                v-for="(product, index) in products"
                :key="index"
                cols="12"
                sm="6"
                md="4"
            >
                <v-card class="rounded-xl" elevation="2" >
                    <v-img
                        @click="openProductDialog(product)" 
                        :src="product.image"
                        height="200"
                        class="rounded-t-xl"
                        contain
                        style="object-fit: contain;cursor: pointer;"
                    ></v-img>
                    <!-- titulo -->
                    <v-card-title @click="openProductDialog(product)" style="cursor: pointer;">{{ product.title }}</v-card-title>
                    <!-- Precio -->
                    <v-card-subtitle class="text-grey-darken-1">
                        ${{ product.price }}
                    </v-card-subtitle>
                    <!-- Botón para agregar al carrito -->
                    <v-card-actions>
                        <v-btn color="primary" @click="addToCart(product)">
                            <v-icon start>mdi-cart-plus</v-icon>
                            Agregar al carrito
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>

        <!-- Diálogo de producto -->
        <v-dialog v-model="showProductDialog" max-width="500">
            <v-card>
                <v-img
                    :src="selectedProduct?.image"
                    height="200"
                    class="rounded-t-xl"
                    contain
                    style="object-fit: contain;"
                ></v-img>

                <v-card-title>{{ selectedProduct?.title }}</v-card-title>
                <v-card-subtitle class="text-grey-darken-1 mb-2">
                    ${{ selectedProduct?.price }}
                </v-card-subtitle>

                <v-card-text>
                    <p>{{ selectedProduct?.description }}</p>
                </v-card-text>

                <v-card-actions class="justify-end">
                    <v-btn color="grey" @click="showProductDialog = false">Cerrar</v-btn>
                    <v-btn color="primary" @click="addToCart(selectedProduct)">
                        <v-icon start>mdi-cart-plus</v-icon>
                        Agregar al carrito
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <!-- Mini carrito -->
        <v-dialog v-model="showCart" max-width="600">
            <v-card>
                <v-card-title class="justify-space-between">
                <span v-if="cart">Tu carrito ({{ cart.length }} productos)</span>
                </v-card-title>

                <v-card-text>
                <template v-if="cart && cart.length > 0">
                    <!-- Encabezados -->
                    <v-row class="font-weight-bold text-caption mb-2">
                    <v-col cols="5">Nombre</v-col>
                    <v-col cols="3">Precio</v-col>
                    <v-col cols="2">Cantidad</v-col>
                    <v-col cols="2" class="text-center">Eliminar</v-col>
                    </v-row>

                    <!-- Items -->
                    <v-row
                    v-for="(item, index) in cart"
                    :key="index"
                    class="py-1 align-center"
                    >
                    <v-col cols="5">{{ item.title }}</v-col>
                    <v-col cols="3">${{ item.price }}</v-col>
                    <v-col cols="2">1</v-col>
                    <v-col cols="2" class="text-center">
                        <v-btn icon @click="removeFromCart(index)">
                        <v-icon color="red">mdi-delete</v-icon>
                        </v-btn>
                    </v-col>
                    </v-row>

                    <!-- Separador -->
                    <v-divider class="my-4"></v-divider>

                    <!-- Total -->
                    <div class="d-flex justify-end font-weight-bold">
                    Total: ${{ totalCarrito }}
                    </div>
                </template>

                <div v-else class="text-center text-grey">Tu carrito está vacío.</div>
                </v-card-text>

                <v-card-actions class="justify-end">
                <v-btn color="grey" @click="showCart = false">Cerrar</v-btn>
                <v-btn color="primary" @click="checkout">Pagar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        
        <v-snackbar
            v-model="snackbar"
            :timeout="timeout"
            color="success"
        >
            {{ text }}

            <template v-slot:actions>
                <v-btn
                    color="success"
                    variant="text"
                    @click="snackbar = false"
                >
                    Close
                </v-btn>
            </template>
        </v-snackbar>
    </v-container>
</template>


<script setup>
    import { ref, onMounted , computed } from 'vue'
    import axios from 'axios'
    import router from '../router'

    const API_URL = '/api/products' 

    const title = 'Bienvenido a mi tienda'

    const products = ref([])
    const allProducts = ref([])     
    const itemsPerPage = 6           
    const currentPage = ref(1)      
    const showCart = ref(false)
    const isLoadingMore = ref(false)
    const cart = ref([])
    const selectedProduct = ref(null)
    const showProductDialog = ref(false)

    const snackbar = ref(false)
    const text = ref('Se agrego el producto al carrito .')
    const timeout = ref(2000)

    const openProductDialog = (product) => {
        selectedProduct.value = product
        showProductDialog.value = true
    }

    const goToLogin = () => {
        router.push('/login')
    }

    const addToCart = (product) => {
        cart.value.push(product)
        showProductDialog.value = false
        snackbar.value = true
    }

    const removeFromCart = (index) => {
        cart.value.splice(index, 1)
    }

    onMounted(async () => {
        try {
            const { data } = await axios.get(API_URL)
            products.value = Array.isArray(data.products) ? data.products : []
        } catch (error) {
            console.error('Error cargando productos:', error)
        }
    })
    const checkout = async () => {
        const token = localStorage.getItem('token')
        const user = JSON.parse(localStorage.getItem('user'))

        if (!token || !user) {
            alert('Debes iniciar sesión para completar la compra.')
            router.push('/login')
            return
        }

        try {
            const response = await axios.post(
                '/api/orders',
                {
                    productos: cart.value,
                    total: totalCarrito.value,
                    user_id: user._id || user.id,
                },
                {
                    headers: {
                    Authorization: `Bearer ${token}`,
                    },
                }
            )

            if (response.status === 201 || response.data.success) {
                alert('¡Orden creada con éxito!')
                cart.value = []
                showCart.value = false
                router.push('/orders')
            } else {
                alert('No se pudo crear la orden. Intenta más tarde.')
            }
        } catch (error) {
            console.error(error)
            alert('Error al crear la orden. Intenta nuevamente.')
        }
    }

    const loadMoreProducts = () => {
        isLoadingMore.value = true

        const start = (currentPage.value - 1) * itemsPerPage
        const end = currentPage.value * itemsPerPage
        const nextItems = allProducts.value.slice(start, end)

        products.value.push(...nextItems)
        currentPage.value++
        isLoadingMore.value = false
    }

    onMounted(async () => {
        try {
            const { data } = await axios.get(API_URL)
            allProducts.value = data.products
            // cargar los primeros productos
            loadMoreProducts() 
        } catch (error) {
            console.error('Error cargando productos:', error)
        }
    })
    
    const totalCarrito = computed(() => {
        return cart.value.reduce((total, item) => total + item.price, 0).toFixed(2)
    })
</script>
