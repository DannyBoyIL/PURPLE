<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Services\UserService;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\Deal as DealResource;
use App\Http\Traits\Response as ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller {

    use ResponseTrait;

    /**
     * Get all users.
     * 
     * @return JsonResponse
     */
    public function index(): JsonResponse {
        $users = User::all();
        return $this->sendResponse(UserResource::collection($users), 'Users retrieved successfully.');
    }

    /**
     * Read user data.
     * 
     * @param User $user
     * @return JsonResponse
     */
    public function show(User $user): JsonResponse {
        return is_null($user) ?
                $this->sendError('User not found.') :
                $this->sendResponse(new UserResource($user), 'User retrieved successfully.', 201);
    }

    /**
     * Update user data. (name and/or image)
     * 
     * @param Request $request
     * @param User $user
     * @param UserService $service
     * @return JsonResponse
     */
    public function update(Request $request, User $user, UserService $service): JsonResponse {
        $validator = Validator::make($request->all(), [
                    'name' => 'present|required_if:image,null',
                    'image' => 'required_if:name,null|image' // present
        ]);
        return !$validator->fails() ?
                $service->update($request, $user) :
                $this->sendError('Validation Error.', $validator->errors());
    }

    /**
     * Create Items on Click.
     * 
     * @param User $user
     * @param UserService $service
     * @return JsonResponse
     */
    public function items(User $user, UserService $service): JsonResponse {
        return is_null($user->items) ?
                $service->items($user) :
                $this->sendError('User already have items.');
    }

    /**
     * Read user deals history.
     * 
     * @param User $user
     * @return JsonResponse
     */
    public function deals(User $user): JsonResponse {
         return is_null($user) ?
                $this->sendError('Deals not found.') :
                $this->sendResponse(DealResource::collection($user->deals), 'Deals retrieved successfully.', 201);
    }

    /**
     * Create bid.
     * 
     * @param Request $request
     * @param User $user
     * @param UserService $service
     * @return JsonResponse
     */
    public function bid(Request $request, User $user, UserService $service): JsonResponse {
        $validator = Validator::make($request->all(), [
                    'data' => 'required|json',
        ]);
        return !$validator->fails() ?
                $service->bid($request, $user) :
                $this->sendError('Validation Error.', $validator->errors());
    }

    /**
     * Create trade.
     * 
     * @param Request $request
     * @param User $user
     * @param UserService $service
     * @return JsonResponse
     */
    public function trade(Request $request, User $user, UserService $service): JsonResponse {
        $validator = Validator::make($request->all(), [
                    'bid_id' => 'required|integer|min:1|exists:trades,id',
                    'data' => 'required|json',
        ]);
        return !$validator->fails() ?
                $service->trade($request, $user) :
                $this->sendError('Validation Error.', $validator->errors());
    }

}
