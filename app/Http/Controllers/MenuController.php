<?php

namespace App\Http\Controllers;

use App\Models\Cuisine;
use App\Models\Menu;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MenuController extends Controller
{

    //Render the setMenus react component
    public function index(Request $request): Response
    {
        return Inertia::render('SetMenus');
    }

    // This action returns filtered & paginated Menus and Cuisines
    public function getFilteredSetMenus(Request $request): JsonResponse
    {
        // cuisineSlug query parameter
        $cuisineSlug = $request->query('cuisineSlug');

        //Fetch SetMenus with Filters
        $menusQuery = Menu::with('cuisines')
            ->where('status', 1) // Menu with status set to 1 only
            ->when($cuisineSlug, function ($query, $slug) {
                $query->whereHas('cuisines', function ($query) use ($slug) {
                    $query->where('name', 'like', $slug);
                });
            })
            ->orderBy('number_of_orders', 'desc'); // Sorted by popularity


        //Paginate Result
        $paginatedMenus = $menusQuery->paginate(20);

        //Append query string to pagination links
        $paginatedMenus->appends(['cuisineSlug' => $request->query('cuisineSlug')]);

        //Fetch Cuisine Filters
        $cuisines = Cuisine::withCount([
            'menus as set_menus_count' => function ($query) {
                $query->where('status', 1);
            },
        ])
            ->withSum('menus as number_of_orders', 'number_of_orders')
            ->orderBy('number_of_orders', 'desc') // Sorted by aggregated orders
            ->get();

        //response
        $response = [
            'filters' => [
                'cuisines' => $cuisines->map(function ($cuisine) {
                    return [
                        'name' => $cuisine->name,
                        'slug' => strtolower(str_replace(" ", "_", $cuisine->name)),
                        'number_of_orders' => $cuisine->number_of_orders,
                        'set_menus_count' => $cuisine->set_menus_count,
                    ];
                }),
            ],
            'setMenus' => collect($paginatedMenus->items())->map(function ($menu) {
                return [
                    'name' => $menu->name,
                    'description' => $menu->description,
                    'price' => $menu->price_per_person,
                    'minSpend' => $menu->min_spend,
                    'thumbnail' => $menu->thumbnail,
                    'cuisines' => $menu->cuisines->map(function ($cuisine) {
                        return [
                            'name' => $cuisine->name,
                            'slug' => strtolower(str_replace(" ", "_", $cuisine->name)),
                        ];
                    }),
                ];
            }),
            'links' => [
                'first' => $paginatedMenus->url(1),
                'last' => $paginatedMenus->url($paginatedMenus->lastPage()),
                'prev' => $paginatedMenus->previousPageUrl(),
                'next' => $paginatedMenus->nextPageUrl(),
            ],
            'meta' => [
                'current_page' => $paginatedMenus->currentPage(),
                'from' => $paginatedMenus->firstItem(),
                'last_page' => $paginatedMenus->lastPage(),
                'links' => $paginatedMenus->linkCollection(),
                'path' => $paginatedMenus->path(),
                'per_page' => $paginatedMenus->perPage(),
                'to' => $paginatedMenus->lastItem(),
                'total' => $paginatedMenus->total(),
            ],
        ];

        return response()->json($response, 200);
    }
}
