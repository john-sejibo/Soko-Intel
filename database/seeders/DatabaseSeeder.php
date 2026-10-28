<?php
namespace Database\Seeders;

use App\Models\{Category, Market, PriceRecord, Product, Seller, User};
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Imeshaendeshwa tayari? Usirudie (migrate --seed inaendeshwa kila deploy).
        if (User::where('role', 'admin')->exists()) {
            return;
        }

        $adminPassword = getenv('ADMIN_PASSWORD') ?: null;
        if (! $adminPassword) {
            if (app()->environment('production')) {
                throw new \RuntimeException('Weka ADMIN_PASSWORD kwenye environment variables kabla ya deploy ya kwanza.');
            }
            $adminPassword = 'password'; // local tu
        }

        User::create([
            'name' => 'Admin',
            'email' => getenv('ADMIN_EMAIL') ?: 'admin@example.com',
            'password' => $adminPassword,
            'role' => 'admin',
        ]);

        $markets = collect([
            ['Kariakoo', 'Ilala', -6.8184, 39.2745],
            ['Machinga Complex', 'Ilala', -6.8167, 39.2833],
            ['Soko la Karume', 'Ilala', -6.8227, 39.2790],
            ['Mwenge', 'Kinondoni', -6.5950, 39.2300],
        ])->map(fn ($m) => Market::create(['name' => $m[0], 'area' => $m[1], 'latitude' => $m[2], 'longitude' => $m[3]]));

        $cat = Category::create(['name' => 'Computer Accessories', 'slug' => 'computer-accessories']);

        // Data ya MFANO (bei za kubuni): washa kwa SEED_DEMO=true. Usiiache kwenye site halisi.
        if (filter_var(getenv('SEED_DEMO'), FILTER_VALIDATE_BOOLEAN)) {
            $items = [
                ['RAM DDR4 16GB', 'Kingston', 85000], ['SSD 256GB SATA', 'Samsung', 60000],
                ['Wireless Mouse', 'Logitech', 18000], ['Laptop Charger 65W', 'HP', 35000],
                ['HP LaserJet P1102w', 'HP', 320000], ['USB Flash 32GB', 'SanDisk', 15000],
            ];
            $products = collect($items)->map(fn ($i) => Product::create([
                'category_id' => $cat->id, 'name' => $i[0], 'brand' => $i[1], 'slug' => Str::slug($i[0]),
            ]));

            $demoPassword = Str::random(16); // wauzaji wa mfano hawawezi kuingia kiholela
            $sellers = $markets->map(function ($m, $k) use ($demoPassword) {
                $u = User::create(['name' => "Seller {$m->name}", 'email' => 'seller'.($k + 1).'@example.com', 'password' => $demoPassword, 'role' => 'seller']);
                return Seller::create(['user_id' => $u->id, 'market_id' => $m->id, 'business_name' => "{$m->name} Traders", 'is_verified' => true]);
            });

            foreach ($products as $pi => $product) {
                foreach ($sellers as $si => $seller) {
                    $price = $items[$pi][2] * (1 + ($si - 1) * 0.04);
                    for ($d = 30; $d >= 0; $d -= 3) {
                        $price *= 1 + mt_rand(-20, 25) / 1000;
                        PriceRecord::create([
                            'product_id' => $product->id, 'seller_id' => $seller->id, 'market_id' => $seller->market_id,
                            'price' => (int) (round($price / 500) * 500), 'stock_qty' => mt_rand(3, 40),
                            'source' => 'seller', 'recorded_at' => now()->subDays($d)->setTime(mt_rand(8, 17), mt_rand(0, 59)),
                        ]);
                    }
                }
            }
        }
    }
}
