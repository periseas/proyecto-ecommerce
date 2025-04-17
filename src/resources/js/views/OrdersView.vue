<template>
    <v-container>
        <h2 class="text-h5 mb-4">Mis Órdenes</h2>
    
        <v-alert
            v-if="!loading && orders.length === 0"
            type="info"
            border="start"
            elevation="2"
        >
            No tienes órdenes registradas aún.
        </v-alert>
    
        <!-- Tabla de Órdenes -->
        <v-data-table
            v-if="orders.length > 0"
            :headers="headers"
            :items="orders"
            item-value="id"
            class="elevation-1"
        >
            <template v-slot:item.date="{ item }">
                <span>{{ item.date }}</span>
            </template>

            <template v-slot:item.items="{ item }">
                <ul class="text-caption" style="list-style-type: none; padding-left: 0;">
                    <li v-for="(product, idx) in item.items" :key="idx">
                        {{ product.title }} - ${{ product.price }}
                    </li>
                </ul>
            </template>

            <template v-slot:item.total="{ item }">
                <div class="text-right font-weight-bold">
                    ${{ item.total}}
                </div>
            </template>

            <template v-slot:item.status="{ item }">
                <v-chip
                    :color="item.status === 'completado' ? 'green' :item.status === 'cancelada'? 'red': 'orange'"
                    text-color="white"
                    size="small"
                >
                    {{ item.status }}
                </v-chip>
            </template>
            <template v-slot:item.actions="{ item }">

                <v-btn v-if="item.status === 'pendiente'" icon @click="cancelOrder(item.id)">
                    <v-icon color="red">mdi-cancel</v-icon>
                </v-btn>

                <v-btn icon @click="viewOrderDetails(item)">
                    <v-icon color="primary">mdi-eye</v-icon>
                </v-btn>
            </template>

        </v-data-table>
    
        <v-progress-circular
            v-if="loading"
            indeterminate
            color="primary"
            class="d-block mx-auto my-6"
        />
        <v-dialog v-model="showCancelDialog" max-width="400">
            <v-card>
                <v-card-title class="text-h6">¿Cancelar esta orden?</v-card-title>
                <v-card-text>
                    Esta acción marcará la orden como <strong>cancelada</strong>. ¿Estás segura?
                </v-card-text>

                <v-card-actions class="justify-end">
                    <v-btn color="grey" variant="text" @click="showCancelDialog = false">Cerrar</v-btn>
                    <v-btn color="red" @click="confirmCancel">Sí, cancelar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="showDetailDialog" max-width="600">
            <v-card>
                <v-card-title class="text-h6">Detalle de la Orden</v-card-title>
                <v-card-text>
                    <v-row >
                        <v-col
                            cols="12"
                            sm="4"
                        >
                            <p class="text-caption">
                                Id de Orden: {{ selectedOrder.id }}
                            </p>
                        </v-col>
                        <v-col
                            cols="12"
                            sm="4"
                        >
                            <p class="text-caption">
                                Fecha: {{ selectedOrder.date }}
                            </p>
                        </v-col>
                        <v-col
                            cols="12"
                            sm="4"
                        >
                            <p class="text-caption">
                                Estatus: {{ selectedOrder.status }}
                            </p>
                        </v-col>
                        <v-col
                            cols="12"
                            sm="12"
                        >
                            <!-- Encabezados -->
                            <v-row class="font-weight-bold text-caption mb-2">
                                <v-col cols="4">Imagen</v-col>
                                <v-col cols="4">Nombre</v-col>
                                <v-col cols="2">Precio</v-col>
                                <v-col cols="2">Cantidad</v-col>
                            </v-row>

                            <!-- Items -->
                            <v-row
                                v-for="(item, index) in selectedOrder.items"
                                :key="index"
                                class="py-1 align-center"
                            >
                                <v-col cols="4">
                                    <v-img
                                        :src="item.image"
                                        height="75"
                                        class="rounded-t-xl"
                                        contain
                                        style="object-fit: contain;"
                                    ></v-img>
                                </v-col>
                                <v-col cols="4">{{ item.title }}</v-col>
                                <v-col cols="2">${{ item.price }}</v-col>
                                <v-col cols="2">1</v-col>
                            </v-row>

                            <v-divider class="my-4"></v-divider>

                            <!-- Total -->
                            <div class="d-flex justify-end font-weight-bold">
                                Total: ${{ selectedOrder.total }}
                            </div>
                        </v-col>
                    </v-row>
                </v-card-text>
                <v-card-actions class="justify-end">
                    <v-btn color="primary" @click="showDetailDialog = false">Cerrar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-container>
</template>
<script setup>
    import { ref, onMounted } from 'vue'
    import axios from 'axios'
    
    const orders = ref([])
    const loading = ref(false)
    const showCancelDialog = ref(false)
    const showDetailDialog = ref(false)
    const selectedOrder = ref([])
    const orderIdToCancel = ref(null)

    const headers = [
        { text: 'ID', align: 'start', key: 'id', value: 'id' },
        { text: 'Fecha', align: 'start', value: 'date' },
        { text: 'Productos', align: 'start', value: 'items' },
        { text: 'Total', align: 'end', value: 'total' },
        { text: 'Estado', align: 'start', value: 'status' },
        { text: 'Acciones', align: 'start', value: 'actions' },
        
    ]
    
    onMounted(async () => {
        loading.value = true
        try {
        const response = await axios.get('/api/orders', {
            headers: {
                Authorization: `Bearer ${localStorage.getItem('token')}`,
            },
        })
        orders.value = response.data
        } catch (error) {
            console.error('Error cargando órdenes:', error)
            orders.value = []
        } finally {
            loading.value = false
        }
    })
    
    
    const viewOrderDetails = (order) => {
        console.log(order)
        selectedOrder.value = order
        console.log(selectedOrder.value)
        showDetailDialog.value = true
    }

    const cancelOrder = (orderId) => {
        orderIdToCancel.value = orderId
        showCancelDialog.value = true
    }

    const confirmCancel = async () => {
        try {
            await axios.patch(`/api/orders/${orderIdToCancel.value}/cancel`, null, {
                headers: {
                    Authorization: `Bearer ${localStorage.getItem('token')}`,
                },
            })
            alert('Orden cancelada correctamente')
            showCancelDialog.value = false
            await fetchOrders()
        } catch (error) {
            alert('Error al cancelar orden')
            console.error(error)
        }
    }

    const fetchOrders = async () => {
        loading.value = true
        try {
            const response = await axios.get('/api/orders', {
                headers: {
                Authorization: `Bearer ${localStorage.getItem('token')}`,
                },
            })
            orders.value = response.data
        } catch (error) {
            console.error('Error cargando órdenes:', error)
        } finally {
            loading.value = false
        }
    }
</script>