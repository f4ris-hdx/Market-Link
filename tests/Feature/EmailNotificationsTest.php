<?php

namespace Tests\Feature;

use App\Mail\FarmerAccountApprovedMail;
use App\Mail\OrderAcceptedMail;
use App\Mail\OrderPlacedMail;
use App\Mail\WelcomeUserMail;
use App\Models\Farmer;
use App\Models\Market;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EmailNotificationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_sends_welcome_email(): void
    {
        Mail::fake();

        $response = $this->post('/register', [
            'name' => 'Jane Customer',
            'email' => 'jane@example.com',
            'phone' => '123456789',
            'role' => 'customer',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertRedirect('/dashboard');
        Mail::assertSent(WelcomeUserMail::class, fn ($mail) => $mail->hasTo('jane@example.com'));
    }

    public function test_order_placement_sends_confirmation_email_to_customer(): void
    {
        Mail::fake();

        $market = Market::factory()->create();
        $customer = User::factory()->create([
            'role' => 'customer',
            'email' => 'customer@example.com',
        ]);
        $farmerUser = User::factory()->create(['role' => 'farmer', 'email' => 'farmer@example.com']);
        $farmer = Farmer::factory()->create([
            'user_id' => $farmerUser->id,
            'status' => 'verified',
            'market_id' => $market->id,
        ]);
        $product = Product::query()->create([
            'farmer_id' => $farmer->id,
            'category_id' => 1,
            'name' => 'Fresh Apples',
            'description' => 'Sweet local apples',
            'unit' => 'kg',
            'price' => 12.50,
            'stock' => 20,
            'status' => 'approved',
        ]);
        $product->markets()->sync([$market->id => ['status' => 'approved', 'reviewed_at' => now()]]);

        $this->actingAs($customer);
        session()->put('cart', [$product->id => 2]);

        $response = $this->post('/checkout', [
            'market_id' => $market->id,
            'pickup_name' => 'Jane Customer',
            'pickup_phone' => '123456789',
            'slot' => 'Saturday 09:00',
            'pickup_date' => '2026-10-05',
        ]);

        $response->assertRedirect('/dashboard');
        Mail::assertSent(OrderPlacedMail::class, fn ($mail) => $mail->hasTo('customer@example.com'));
    }

    public function test_order_acceptance_sends_email_to_customer(): void
    {
        Mail::fake();

        $market = Market::factory()->create();
        $customer = User::factory()->create(['role' => 'customer', 'email' => 'customer-accept@example.com']);
        $farmerUser = User::factory()->create(['role' => 'farmer', 'email' => 'farmer-accept@example.com']);
        $farmer = Farmer::factory()->create([
            'user_id' => $farmerUser->id,
            'status' => 'verified',
            'market_id' => $market->id,
        ]);
        $product = Product::query()->create([
            'farmer_id' => $farmer->id,
            'category_id' => 1,
            'name' => 'Fresh Tomatoes',
            'description' => 'Farm-fresh tomatoes',
            'unit' => 'kg',
            'price' => 10.00,
            'stock' => 30,
            'status' => 'approved',
        ]);

        $order = Order::query()->create([
            'order_number' => 'MK-TEST-01',
            'user_id' => $customer->id,
            'market_id' => $market->id,
            'slot' => 'Saturday 10:00',
            'pickup_name' => 'Jane Customer',
            'pickup_phone' => '123456789',
            'total' => 20.00,
            'status' => 'Placed',
            'placed_at' => now()->toDateString(),
        ]);

        OrderItem::query()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'name' => $product->name,
            'unit' => 'kg',
            'price' => 10.00,
            'qty' => 2,
        ]);

        $this->actingAs($farmerUser);

        $response = $this->post('/farmer/orders/'.$order->id, [
            'status' => 'Accepted',
        ]);

        $response->assertRedirect();
        Mail::assertSent(OrderAcceptedMail::class, fn ($mail) => $mail->hasTo('customer-accept@example.com'));
    }

    public function test_farmer_approval_email_is_sent_after_admin_verification(): void
    {
        Mail::fake();

        $admin = User::factory()->create(['role' => 'admin', 'email' => 'admin@example.com']);
        $farmerUser = User::factory()->create(['role' => 'farmer', 'email' => 'approved-farmer@example.com']);
        $farmer = Farmer::factory()->create([
            'user_id' => $farmerUser->id,
            'status' => 'pending',
            'market_id' => Market::factory()->create()->id,
        ]);

        $this->actingAs($admin);

        $response = $this->post('/admin/farmers/'.$farmer->id.'/verify');

        $response->assertRedirect();
        Mail::assertSent(FarmerAccountApprovedMail::class, fn ($mail) => $mail->hasTo('approved-farmer@example.com'));
    }
}
