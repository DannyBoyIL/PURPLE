<?php

namespace App\Http\Controllers;

use App\Models\Trade;
use App\Http\Resources\Trade as TradeResource;
use App\Http\Traits\Response as ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TradeController extends Controller
{
    use ResponseTrait;
    
    /**
     * Get all trades.
     * 
     * @return JsonResponse
     */
    public function index(): JsonResponse {
        $trades = Trade::all();
        return $this->sendResponse(TradeResource::collection($trades), 'Trades retrieved successfully.');
    }
}
