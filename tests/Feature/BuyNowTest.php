<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BuyNowTest extends TestCase
{
    use RefreshDatabase;

    public function test_buy_now_uses_a_temporary_selection_without_changing_the_cart(): void
    {
        $category = Category::create(['name' => 'Bienestar', 'slug' => 'bienestar']);
        $product = Product::create([
            'category_id' => $category->id,
            'slug' => 'berry-coffee',
            'name' => 'Berry Coffee',
            'price' => 96000,
            'source_page' => 1,
        ]);

        $response = $this->post(route('cart.store', $product), [
            'quantity' => 2,
            'buy_now' => 1,
        ]);

        $response->assertRedirect(route('checkout.index', ['mode' => 'buy-now']));
        $response->assertSessionMissing('cart');
        $response->assertSessionHas('buy_now', ['product_id' => $product->id, 'quantity' => 2]);

        $this->get(route('checkout.index', ['mode' => 'buy-now']))
            ->assertOk()
            ->assertSee('Berry Coffee')
            ->assertSee('$ 192.000');

        $this->get(route('cart.index'))
            ->assertOk()
            ->assertSee('Tu carrito está vacío');
    }
}
