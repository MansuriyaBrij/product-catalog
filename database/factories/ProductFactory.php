<?php

namespace Database\Factories;

use App\Models\Subcategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        // Generate a fake image and store it
        $imageNumber = rand(1, 5);  // We'll create 5 placeholder images
        $imagePath = "products/product{$imageNumber}.jpg";
        
        // Ensure the products directory exists
        if (!Storage::disk('public')->exists('products')) {
            Storage::disk('public')->makeDirectory('products');
        }
        
        // Copy placeholder image if it doesn't exist
        if (!Storage::disk('public')->exists($imagePath)) {
            // Create a colored rectangle as a placeholder
            $image = imagecreatetruecolor(800, 600);
            $color = imagecolorallocate($image, rand(0, 255), rand(0, 255), rand(0, 255));
            imagefill($image, 0, 0, $color);
            
            // Add some text
            $textColor = imagecolorallocate($image, 255, 255, 255);
            imagestring($image, 5, 300, 280, 'Product Image', $textColor);
            
            // Save the image
            ob_start();
            imagejpeg($image);
            $imageData = ob_get_clean();
            Storage::disk('public')->put($imagePath, $imageData);
            imagedestroy($image);
        }

        return [
            'name' => fake()->productName ?? fake()->words(3, true),
            'description' => fake()->paragraph(),
            'price' => fake()->randomFloat(2, 10, 1000),
            'image_path' => $imagePath,
            'is_active' => fake()->boolean(80), // 80% chance of being active
            'subcategory_id' => Subcategory::factory(),
        ];
    }
}
