<?php
namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Menu;
use App\Models\Cuisine;
use App\Models\Group;
use Illuminate\Support\Sleep;

class GetMenus extends Command
{
    protected $signature = 'get:menus';
    protected $description = 'Fetch menus from the API and store in the database';

    /**
     * @throws Exception
     */
    public function handle(): void
    {
        $apiEndpoint = 'https://staging.yhangry.com/booking/test/set-menus';
        $currentPage = 1;
        $hasMorePages = true;

        while ($hasMorePages) {
            $response = Http::get("$apiEndpoint?page=$currentPage");

            $this->info("fetching $apiEndpoint?page=$currentPage...");

            if ($response->failed()) {
                $this->error("Failed to fetch page $currentPage.");
                break;
            }

            $data = $response->json();

            // Process the current page's data
            foreach ($data['data'] as $menuData) {
                $this->processMenuData($menuData);
            }

            // Check if more pages are available
            $hasMorePages = $data['meta']['current_page'] < $data['meta']['last_page'];
            $currentPage++;
            Sleep::for(1)->seconds();
        }

        $this->info('All menu items have been fetched and processed successfully.');

    }

    public function processMenuData(array $menuData): void
    {
        $menu = Menu::updateOrCreate(
            ['name' => $menuData['name']],
            [
                'description' => $menuData['description'],
                'display_text' => $menuData['display_text'],
                'price_per_person' => $menuData['price_per_person'],
                'min_spend' => $menuData['min_spend'],
                'is_vegan' => $menuData['is_vegan'],
                'is_vegetarian' => $menuData['is_vegetarian'],
                'is_seated' => $menuData['is_seated'],
                'is_standing' => $menuData['is_standing'],
                'is_canape' => $menuData['is_canape'],
                'is_mixed_dietary' => $menuData['is_mixed_dietary'],
                'is_meal_prep' => $menuData['is_meal_prep'],
                'is_halal' => $menuData['is_halal'],
                'is_kosher' => $menuData['is_kosher'],
                'image' => $menuData['image'],
                'thumbnail' => $menuData['thumbnail'],
                'status' => $menuData['status'],
                'available' => $menuData['available'],
                'number_of_orders' => $menuData['number_of_orders'],
                'created_at' => $menuData['created_at'],
            ]
        );

        foreach ($menuData['cuisines'] as $cuisineData) {
            if (isset($cuisineData['id']) && isset($cuisineData['name'])) {
                $cuisine = Cuisine::firstOrCreate(
                    ['id' => $cuisineData['id']],
                    ['name' => $cuisineData['name']]
                );
                $menu->cuisines()->syncWithoutDetaching([$cuisine->id]);
            }else{
                throw new Exception('Invalid cuisine data: ID or Name is missing.');
            }
        }

        $groupsData = [
            'menu_id' => $menu->id,
        ];
        foreach ($menuData['groups'] as $key => $groupData) {
            $groupData = is_array($groupData) ? json_encode($groupData) : $groupData;
            $groupsData[$key] = $groupData;
        }

        Group::updateOrCreate(
            ['menu_id' => $menu->id],
            $groupsData
        );
    }
}
