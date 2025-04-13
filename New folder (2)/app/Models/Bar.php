<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Category;

class Bar extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $casts = [
        'items' => 'json',
    ];

    public function setHeaderItems()
    {
        $newItems = [];

        foreach ($this->items as $item) {
            if ($item['type'] == 'multi') {
                $newSubItems = [];

                foreach ($item['items'] as $subItem) {
                    $childItem = [];

                    if ($subItem['type'] == 'categ') {
                        $category = Category::find($subItem['value']);
                        if (!$category) {
                            continue;
                        }
                        $childItem['type'] = 'categ';
                        $childItem['id'] = $category->id;
                        $childItem['name'] = $category->name;
                        $childItem['slug'] = $category->slug;
                    } else {
                        $childItem['type'] = 'link';
                        $childItem['name'] = $subItem['name'];
                        $childItem['link'] = $subItem['link'];
                    }

                    $newSubItems[] = $childItem;
                }

                $item['items'] = $newSubItems;
            } elseif ($item['type'] == 'single') {
                $childItem = [];

                if ($item['item']['type'] == 'categ') {
                    $category = Category::find($item['item']['value']);
                    $childItem['type'] = 'categ';
                    $childItem['id'] = $category->id;
                    $childItem['name'] = $category->name;
                    $childItem['slug'] = $category->slug;
                } else {
                    $childItem['type'] = 'link';
                    $childItem['name'] = $item['item']['name'];
                    $childItem['link'] = $item['item']['link'];
                }

                $item['item'] = $childItem;
            }

            $newItems[] = $item;
        }

        $this->attributes['items'] = json_encode($newItems);
    }
}
