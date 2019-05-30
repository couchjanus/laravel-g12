<?php

namespace App\Gadgets;

use App\Gadgets\Contracts\GadgetContract;
use App\Category;

class CategoriesGadget implements GadgetContract
{
    public function execute()
    {
        // $cats = \App\Post::where('status',2)->get('category_id');

        $categories = \App\Category::find(\App\Post::where('status',2)->get('category_id'));
        
        return view('gadgets::categories', [
            'data' => $categories
            ]
        );
    }
}
