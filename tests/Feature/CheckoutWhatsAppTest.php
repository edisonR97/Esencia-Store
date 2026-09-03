<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutWhatsAppTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_redirects_to_configured_whatsapp_with_order_details(): void
    {
        config(['store.whatsapp' => '573238080851']);

        $category = Category::create(['name' => 'Cuidado', 'slug' => 'cuidado']);
        $product = Product::create([
            'category_id' => $category->id,
            'slug' => 'producto-prueba',
            'name' => 'Producto de prueba',
            'price' => 25000,
            'source_page' => 1,
        ]);

        $response = $this->withSession(['cart' => [$product->id => 2]])->post(route('checkout.store'), [
            'name' => 'Cliente de prueba',
            'phone' => '3001234567',
            'department' => 'Antioquia',
            'city' => 'Medellín',
            'address' => 'Calle 1 # 2-3',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
        $location = $response->headers->get('Location');
        $this->assertStringStartsWith('https://wa.me/573238080851?text=', $location);
        $message = rawurldecode(parse_url($location, PHP_URL_QUERY));
        $this->assertStringContainsString('Producto de prueba', $message);
        $this->assertStringContainsString('Cantidad: 2', $message);
        $this->assertStringContainsString('50.000', $message);
    }
}
