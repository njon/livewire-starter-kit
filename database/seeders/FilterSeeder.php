<?php

namespace Database\Seeders;

use App\Models\FilterCategory;
use App\Models\FilterOption;
use Illuminate\Database\Seeder;

class FilterSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            'For Who' => [
                'Wife',
                'Husband',
                'Girlfriend',
                'Boyfriend',
                'Brother',
                'Sister',
                'Father',
                'Mother',
                'Son',
                'Daughter',
                'Friend',
                'Colleague'
            ],
            'Age Group' => [
                'Child (3-12)',
                'Teen (13-19)',
                'Young Adult (20-35)',
                'Adult (36-55)',
                'Senior (56-75)',
                'Elder (75+)'
            ],
            'Duration' => [
                '30 minutes',
                '1 hour',
                '2 hours',
                'Half day (4 hours)',
                'Full day',
                'Multiple days'
            ],
            'Athens Regions' => [
                'Acropolis Area',
                'Plaka',
                'Monastiraki',
                'Syntagma',
                'Kolonaki',
                'Exarchia',
                'Omonia',
                'Psiri',
                'Gazi',
                'Kifissia',
                'Glyfada',
                'Vouliagmeni',
                'Piraeus',
                'Nea Smyrni'
            ],
            'Activity Type' => [
                'Water Activities',
                'Sports',
                'Health & Wellness',
                'Adventure',
                'Culinary',
                'Cultural',
                'Art & Craft',
                'Music',
                'Dance',
                'Nature',
                'History',
                'Photography'
            ],

            'Accessibility' => [
                'Wheelchair Accessible',
                'Blind-Friendly',
                'Deaf-Friendly',
                'Senior-Friendly'
            ],
            'Learning Outcomes' => [
                'Educational',
                'Skill-Building',
                'Creative',
                'Physical Development',
                'Emotional Growth',
                'Social Skills'
            ],
            'Special Features' => [
                'Unique Experiences',
                'Luxurious Experiences',
                'Special Offers',
                'Eco-Friendly'
            ]
        ];

        foreach ($categories as $categoryName => $options) {
            // Determine filter type based on category
            $type = 'select';
            if ($categoryName === 'Participants') {
                $type = 'range';
            } elseif (in_array($categoryName, ['Duration', 'Accessibility', 'Special Features'])) {
                $type = 'checkbox';
            }

            // Create the category
            $category = FilterCategory::create([
                'name' => $categoryName,
                'slug' => strtolower(str_replace(' ', '-', $categoryName)),
                'type' => $type,
                'sort_order' => array_search($categoryName, array_keys($categories)) + 1
            ]);

            // Create options for this category
            foreach ($options as $index => $optionName) {
                FilterOption::create([
                    'filter_category_id' => $category->id,
                    'name' => $optionName,
                    'value' => strtolower(str_replace(' ', '-', $optionName)),
                    'sort_order' => $index + 1
                ]);
            }
        }
    }
}