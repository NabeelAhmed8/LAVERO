<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $outerwear = Category::create(['name' => 'Coats & Outerwear', 'slug' => 'outerwear']);
        $tailoring = Category::create(['name' => 'Atelier Tailoring', 'slug' => 'tailoring']);
        $evening = Category::create(['name' => 'Evening Couture', 'slug' => 'evening']);
        $knitwear = Category::create(['name' => 'Cashmere Knits', 'slug' => 'knitwear']);

        $products = [
            [
                'category_id' => $outerwear->id,
                'name' => "L'Éclipse Trench Coat",
                'slug' => 'leclipse-trench-coat',
                'description' => 'Architectural double-breasted trench handcrafted from water-resistant double-faced wool and silk blend. Features dropped structured shoulders, storm flap detailing, and a detachable horn-buckled belt.',
                'price' => 1850.00,
                'material' => 'Double-Faced Wool & Mulberry Silk',
                'tag' => 'Runway Exclusive',
                'image' => 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=1200&q=80',
                'hover_image' => 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=1200&q=80',
                'sizes' => ['S', 'M', 'L'],
                'featured' => true,
            ],
            [
                'category_id' => $tailoring->id,
                'name' => 'Atelier Sculpted Blazer',
                'slug' => 'atelier-sculpted-blazer',
                'description' => 'Precision hourglass blazer featuring hand-padded shoulder canvas and suppressed waist. Woven in Biella, Italy from 100% extra-fine virgin wool with cupro lining.',
                'price' => 1420.00,
                'material' => '100% Italian Virgin Wool',
                'tag' => 'New Drop',
                'image' => 'https://images.unsplash.com/photo-1509631179647-0177331693ae?auto=format&fit=crop&w=1200&q=80',
                'hover_image' => 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=1200&q=80',
                'sizes' => ['XS', 'S', 'M', 'L'],
                'featured' => true,
            ],
            [
                'category_id' => $evening->id,
                'name' => 'Noir Asymmetric Silk Gown',
                'slug' => 'noir-asymmetric-silk-gown',
                'description' => 'Floor-length bias cut gown in heavyweight 40mm silk crepe. Features fluid cowl neckline, single trailing cape sleeve, and thigh-high side slit.',
                'price' => 2600.00,
                'material' => '100% Heavy Mulberry Silk',
                'tag' => 'Couture',
                'image' => 'https://images.unsplash.com/photo-1566174053879-31528523f8ae?auto=format&fit=crop&w=1200&q=80',
                'hover_image' => 'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?auto=format&fit=crop&w=1200&q=80',
                'sizes' => ['36', '38', '40'],
                'featured' => true,
            ],
            [
                'category_id' => $knitwear->id,
                'name' => 'Architectural Cashmere Knit',
                'slug' => 'architectural-cashmere-knit',
                'description' => '7-gauge ribbed high-neck sweater spun from Grade-A Mongolian cashmere. Engineered with elongated split cuffs and relaxed cocoon drape.',
                'price' => 890.00,
                'material' => 'Grade-A Mongolian Cashmere',
                'tag' => 'Essential',
                'image' => 'https://images.unsplash.com/photo-1576995853123-5a10305d93c0?auto=format&fit=crop&w=1200&q=80',
                'hover_image' => 'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?auto=format&fit=crop&w=1200&q=80',
                'sizes' => ['S', 'M', 'L', 'XL'],
                'featured' => true,
            ],
            [
                'category_id' => $tailoring->id,
                'name' => 'Palazzo High-Waist Trousers',
                'slug' => 'palazzo-high-waist-trousers',
                'description' => 'Floor-skimming wide-leg trouser with double forward pleats and clean waistband. Constructed from structured wool-crepe with exceptional movement.',
                'price' => 940.00,
                'material' => 'Structured Crepe & Silk Blend',
                'tag' => 'Tailoring',
                'image' => 'https://images.unsplash.com/photo-1509631179647-0177331693ae?auto=format&fit=crop&w=1200&q=80',
                'hover_image' => 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=1200&q=80',
                'sizes' => ['34', '36', '38', '40'],
                'featured' => true,
            ],
            [
                'category_id' => $outerwear->id,
                'name' => 'Monolith Leather Overcoat',
                'slug' => 'monolith-leather-overcoat',
                'description' => 'Minimalist ankle-length coat crafted from buttery glove-tanned Italian calfskin. Hand-buffed matte black finish with hidden horn closure.',
                'price' => 3200.00,
                'material' => 'Supple Italian Calfskin',
                'tag' => 'Limited 50 Pcs',
                'image' => 'https://images.unsplash.com/photo-1550614000-4895a10e1bfd?auto=format&fit=crop&w=1200&q=80',
                'hover_image' => 'https://images.unsplash.com/photo-1544441893-675973e31985?auto=format&fit=crop&w=1200&q=80',
                'sizes' => ['M', 'L'],
                'featured' => true,
            ],
        ];

        foreach ($products as $data) {
            Product::create($data);
        }
    }
}
