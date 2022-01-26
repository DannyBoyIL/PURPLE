<?php

namespace App\Http\Services;

use App\Models\User;
use App\Models\Trade;
use App\Models\TradesHistory;
use App\Http\Traits\Response as ResponseTrait;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\Deal as DealResource;
use App\Http\Resources\Trade as TradeResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class UserService {

    use ResponseTrait;

    /**
     * Set random items to array.
     * 
     * @staticvar array $array
     * @return array
     */
    private function item(): array {

        static $array = [];

        $items = [
            1 => 'glass',
            2 => 'water',
            3 => 'bread',
            4 => 'knife',
            5 => 'fishing net',
            6 => 'chicken',
            7 => 'cow',
            8 => 'shirt',
            9 => 'trouser',
            10 => 'house'
        ];

        $rand = rand(1, 10);
        $total = 0;
        $amount = 0;
        $valid = true;

        !array_key_exists($items[$rand], $array) ?
                        $array[$items[$rand]] = ['value' => $rand, 'amount' => 1] :
                        $array[$items[$rand]]['amount'] += 1;

        foreach ($array as $item) {
            $total += ($item['value'] * $item['amount']);
            $amount += $item['amount'];
        }

        if (($total < 20 && $amount > 2) || ($total > 20)) {

            $valid = !($total > 20);

            foreach ($array as $i => $item) {
                if ($item['amount'] == 2) {
                    $array[$i]['amount'] = 1;
                    $total -= $item['value'];
                    $amount -= 1;
                    $valid = false;
                }
            }

            if ($amount >= 6) {
                $shift = array_shift($array);
                $total -= ($shift['amount'] * $shift['value']);
                $valid = false;
            }
            return $valid ? $array : $this->item();
        }
        return $this->item();
    }

    /**
     * Insert user items.
     * 
     * @param User $user
     * @return JsonResponse
     */
    public function items(User $user): JsonResponse {

        $user->items = json_encode($this->item());

        if ($user->save()) {
            return $this->sendResponse(new UserResource($user), 'User updated successfully.');
        }
    }

    /**
     * Create or update user details.
     * 
     * @param Request $request
     * @param User $user
     * @return JsonResponse
     */
    public function update(Request $request, User $user): JsonResponse {

        if ($request->hasFile('image')) {

            if ($request->file('image')->isValid()) {

                $image = date('Y.m.d.H.i.s') . '-' . $request->file('image')->getClientOriginalName();
                $request->file('image')->move(storage_path() . '/images', $image);
                $user->image = $image;
            }
        }

        $user->name = $request->name;

        if ($user->save()) {
            return $this->sendResponse(new UserResource($user), 'User updated successfully.');
        }
    }

    /**
     * Create new bid.
     * 
     * @param Request $request
     * @param User $user
     * @return JsonResponse
     */
    public function bid(Request $request, User $user): JsonResponse {

        $data = json_decode($request->data, true);
        $items = json_decode($user->items, true);
        $trades = [];

        foreach ($data as $key => $value) {
            if (!array_key_exists($key, $items)) {
                return $this->sendError(ucfirst($key) . ' isn\'t available for trade.');
            }
            $items[$key]['amount'] = $value;
            $trades[$key] = $items[$key];
        }

        $trade = new Trade();
        $trade->user_id = $user->id;
        $trade->items = json_encode($trades);
        // $trade->created_at = date('Y-m-d h:i:s');

        if ($trade->save()) {
            return $this->sendResponse(new TradeResource($trade), 'Trade created successfully.', 201);
        }
    }

    /**
     * Create new trade.
     * 
     * @param Request $request
     * @param User $user
     * @return JsonResponse
     */
    public function trade(Request $request, User $user): JsonResponse {
        // Check trader items (own items).
        $data = json_decode($request->data, true);
        $traderItems = json_decode($user->items, true);
        $trades = [];
        $totalTrades = 0;

        foreach ($data as $key => $value) {
            if (!array_key_exists($key, $traderItems)) {
                return $this->sendError(ucwords($key) . ' isn\'t available for trade.');
            }
            $totalTrades += ($traderItems[$key]['amount'] * $traderItems[$key]['value']);
            $traderItems[$key]['amount'] = $value;
            $trades[$key] = $traderItems[$key];
        }

        // Check bidder items.
        $trade = Trade::find($request->bid_id);
        $bids = json_decode($trade->items, true);
        $bidderItems = json_decode($trade->user->items, true);
        $totalBids = 0;

        foreach ($bids as $key => $value) {
            // Check Availability. If items doesn't exist on users.items, bid is cancelled and removed from trades.
            if (!array_key_exists($key, $bidderItems)) {
                $trade->delete();
                return $this->sendResponse([new TradeResource($trade)], 'Trade isn\'t valid, deleted successfully.', 201);
            }
            // Check quantity. If not enought items for user on users.items, bid is cancelled and removed from trades.
            if (($bidderItems[$key]['amount'] - $value['amount']) < 0) {
                $trade->delete();
                return $this->sendResponse([new TradeResource($trade)], 'Trade isn\'t valid, deleted successfully.', 201);
            }
            $totalBids += ($bids[$key]['amount'] * $bids[$key]['value']);
        }

        // Check total value.
        if ($totalTrades < $totalBids) {
            return $this->sendError("Total trade ($totalTrades) isn't sufficient for the total bid ($totalBids).");
        }

        DB::beginTransaction();
        try {
            $history = TradesHistory::store($user->id, $trade, $trades);
            $trade->user->updateItems($bidderItems, $bids, $trades);
            $user->updateItems($traderItems, $trades, $bids);
            $trade->delete();
            DB::commit();
            return $this->sendResponse(new DealResource($history), 'New bid created successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->sendError('Internal Server Error', $e, 500);
        }
    }

}
