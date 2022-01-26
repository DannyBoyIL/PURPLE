<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TradesHistory extends Model {

    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'trades_history';    

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function owner() {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }

    static public function store($id, $bid, $trades) {

        $history = new self();
        $history->user_id = $id;
        $history->owner_id = $bid->user_id;
        $history->bid = $bid->items;
        $history->trades = json_encode($trades);
        $history->save();
        
        return $history;
    }

}
