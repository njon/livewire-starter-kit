<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Lunar\Models\Product;
use Lunar\Models\ProductVariant;
use Lunar\FieldTypes\Text;
use Lunar\FieldTypes\TranslatedText;

class ProductsSeeder extends AbstractSeeder
{
    public function run()
    {
        // Load product data from JSON file
        $json = $this->getSeedData('product');

        $products = json_decode($json, true);

        foreach ($products as $productData) {
            $this->createProduct($productData);
        }
    }

    protected function createProduct(array $productData)
    {
        // Get localized names with fallbacks
        $nameEn = $productData['title'] ?? $productData['title_gr'] ?? 'Untitled Product';
        $nameGr = $productData['title_gr'] ?? $nameEn;
        $description = $productData['description'] ?? '';

        // Create the product
        $product = Product::create([
            'product_type_id' => 1, // Set your product type ID
            'status' => 'published',
            'attribute_data' => [
                'name' => new TranslatedText([
                    'en' => new Text($nameEn),
                    'gr' => new Text($nameGr),
                ]),
                'description' => new TranslatedText([
                    'en' => new Text($description),
                    'gr' => new Text($description),
                ]),
            ]
        ]);

        $rootCategories = \Lunar\Models\Collection::whereNull('parent_id')->get();
        $subCategories = \Lunar\Models\Collection::whereNotNull('parent_id')->get();
        $randomCategory = $rootCategories->random();
        $product->collections()->attach($randomCategory->id);
        
        // Attach random subcategory if available (only if it belongs to the selected category)
        if ($subCategories->isNotEmpty()) {
            $validSubCategories = $subCategories->where('parent_id', $randomCategory->id);
            
            if ($validSubCategories->isNotEmpty()) {
                $randomSubCategory = $validSubCategories->random();
                $product->collections()->attach($randomSubCategory->id);
            }
        }

        // Create variant
        $variant = $product->variants()->create([
            'stock' => 500, // Default stock
            'tax_class_id' => 1, // Default tax class
            'attribute_data' => [
                'name' => new TranslatedText([
                    'en' => new Text($nameEn),
                    'gr' => new Text($nameGr),
                ]),
            ]
        ]);

        // Set default price
        $variant->prices()->create([
            'price' => 0, // Default price (update as needed)
            'currency_id' => 1, // Default currency
        ]);

        $this->command->info("Created product: {$nameEn}");
    }
}