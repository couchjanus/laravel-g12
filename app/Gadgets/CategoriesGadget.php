<?php

namespace App\Gadgets;

use App\Gadgets\Contracts\GadgetContract;
use App\Category;

class CategoriesGadget implements GadgetContract
{
    public function execute()
    {
        $data = Category::all();
        return $data;
    }

    // public function execute()
    // {
    //     $data = Category::all();
    //     return view('gadgets::categories', [
    //         'data' => $data
    //         ]
    //     );
    // }
}
