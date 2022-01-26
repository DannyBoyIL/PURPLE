<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model {

    use HasFactory;

    public function deals() {
        return $this->hasMany(TradesHistory::class);
    }

    public function updateItems($items, $deduction, $addition) {

        foreach ($deduction as $key => $value) {
            $items[$key]['amount'] -= $value['amount'];
            if ($items[$key]['amount'] == 0) {
                unset($items[$key]);
            }
        }

        foreach ($addition as $key => $value) {
            if (!array_key_exists($key, $items)) {
                $items[$key] = $value;
            } else {
                $items[$key]['amount'] += $value['amount'];
            }
        }
        $this->items = json_encode($items);
        $this->save();
    }
}
