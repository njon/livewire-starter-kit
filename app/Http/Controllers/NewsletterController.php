<?php

namespace App\Http\Controllers;

use App\Services\NewsletterService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NewsletterController extends Controller
{
    protected NewsletterService $newsletterService;

    public function __construct(NewsletterService $newsletterService)
    {
        $this->newsletterService = $newsletterService;
    }

    public function subscribe(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|max:255',
            'name' => 'nullable|string|max:255',
        ]);

        $result = $this->newsletterService->subscribe(
            $request->input('email'),
            ['name' => $request->input('name')]
        );

        return response()->json($result, $result['success'] ? 200 : 422);
    }

    public function unsubscribe(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $result = $this->newsletterService->unsubscribe($request->input('email'));

        return response()->json($result, $result['success'] ? 200 : 422);
    }

    public function status(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $isSubscribed = $this->newsletterService->isSubscribed($request->input('email'));

        return response()->json([
            'subscribed' => $isSubscribed,
            'message' => $isSubscribed ? 'Email is subscribed' : 'Email is not subscribed'
        ]);
    }
}