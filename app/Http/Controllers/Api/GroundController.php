<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GroundResource;
use App\Models\Ground;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class GroundController extends Controller
{
    public function popular(): AnonymousResourceCollection
    {
        $grounds = Ground::query()
            ->active()
            ->with([
                'media',
                'courts' => fn ($query) => $query->active()->with([
                    'media',
                    'category' => fn ($q) => $q->with('media'),
                ]),
            ])
            ->withCount('bookings')
            ->orderByDesc('bookings_count')
            ->limit(10)
            ->get();

        return GroundResource::collection($grounds);
    }

    public function nearby(Request $request): AnonymousResourceCollection|JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'radius' => ['required', 'numeric', 'min:1', 'max:100'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $radius = $request->radius;

        $grounds = Ground::query()
            ->active()
            ->with([
                'media',
                'courts' => fn ($query) => $query->active()->with([
                    'media',
                    'category' => fn ($q) => $q->with('media'),
                ]),
            ])
            ->select(['*'])
            ->nearBy($latitude, $longitude, $radius)
            ->get();

        return GroundResource::collection($grounds);
    }

    public function byCategory(Request $request): AnonymousResourceCollection|JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'category_id' => ['required', 'int', 'exists:categories,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $grounds = Ground::query()
            ->active()
            ->with('media')
            ->withWhereHas('courts', function ($query) use ($request) {
                $query->where('category_id', $request->integer('category_id'))
                    ->active()
                    ->with([
                        'media',
                    ]);
            })
            ->get();

        return GroundResource::collection($grounds);
    }

    public function show(Request $request): GroundResource|JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'ground_id' => ['required', 'int', 'exists:grounds,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $ground = Ground::query()
            ->with([
                'media',
                'courts' => fn ($query) => $query->active()->with([
                    'media',
                    'category' => fn ($q) => $q->with('media'),
                ]),
            ])
            ->findOrFail($request->integer('ground_id'));

        return new GroundResource($ground);
    }
}
