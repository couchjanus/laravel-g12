<?php

namespace App\Gadgets;

class Gadget
{
    // protected $gadgets;

	// public function __construct($gadgets)
	// {
	// 	$this->gadgets = $gadgets;
    // }
	
	public function show()
	{
		return 'This is Show Method';
	}

	// public function show()
	// {
	// 	// $obj = new \App\Gadgets\CategoriesGadget();
	// 	$obj = new \App\Gadgets\TagsGadget();
	// 	return $obj->execute();
	// }

	// public function show($obj)
	// {
	// 	if(isset($this->gadgets[$obj])) {
	// 		$obj = new $this->gadgets[$obj];
	// 		return $obj->execute();
	// 	}
	// }

	// public function show($obj, $data =[])
	// {
	// 	if(isset($this->gadgets[$obj])){
	// 		$obj = new $this->gadgets[$obj]($data);
	// 		return $obj->execute();
	// 	}
	// }

}
