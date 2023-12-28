<?php

namespace App\Http\Livewire;

use App\Models\Shopping;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ListShoppingComponent extends Component
{
    public function render()
    {

        $shoppings = [];

        $total = 0;

        $user = auth()->user();
        if ($user->hasRole(['buyers-manager', 'seller' ])) {
            $shoppings = Shopping::orderBy('created_at', 'desc')->paginate(8);
            $total = $shoppings->sum('precio_total');
        }else{
            $shoppings = Shopping::where('user_id', $user->id)->orderBy('created_at', 'desc')->paginate(8);
            $total = $shoppings->sum('precio_total');
        }
      
        return view('livewire.list-shopping-component', [
            'shoppings' => $shoppings,
            'total' => $total,
        ]);
    }
}
