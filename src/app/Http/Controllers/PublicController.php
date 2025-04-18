<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicController extends Controller
{
    /**
     * Página pública de inicio con información general y productos destacados
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIndex()
    {
        // Información estática (puedes mover esto a una tabla de configuración o CMS después)
        $info = [
            'title' => 'Bienvenid@ a  Market',
            'subtitle' => 'Descubre productos únicos y personalizados',
            'description' => '',
            'contact_email' => 'soporte@market.com',
            'banner_url' => asset('images/home-banner.jpg'),
        ];

        // Obtener los primeros 3 productos destacados
        $featuredProducts = Product::limit(3)->get();

        return response()->json([
            'success' => true,
            'message' => 'Página pública cargada correctamente',
            'info' => $info,
            'featured_products' => $featuredProducts,
        ]);
    }
}
