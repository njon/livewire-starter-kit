<?php

namespace App\Http\Controllers;

use App\Models\ProductQuestion;
use Illuminate\Http\Request;
use Lunar\Models\Product;

class ProductQuestionController extends Controller
{
    public function index(Product $product)
    {
        $questions = $product->questions()
            ->with(['user', 'answerer'])
            ->answered()
            ->latest()
            ->paginate(10);

        return view('products.questions.index', compact('product', 'questions'));
    }

    public function store(Request $request, Product $product)
    {
        $request->validate([
            'question' => 'required|string|min:10|max:1000',
        ]);

        $question = new ProductQuestion([
            'question' => $request->question,
            'user_id' => auth()->id(),
        ]);

        $product->questions()->save($question);

        return response()->json([
            'success' => true,
            'message' => 'Your question has been submitted!',
        ]);
    }

    public function answer(Request $request, Product $product, ProductQuestion $productquestion)
    {
        $request->validate([
            'answer' => 'required|string|min:10|max:1000',
        ]);

        $productquestion->update([
            'answer' => $request->answer,
            'answered_by' => auth()->id(),
            'answered_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Your question has been submitted!',
        ]);
    }

    public function destroy(ProductQuestion $question)
    {
        $question->delete();

        return redirect()->back()
            ->with('success', 'Question deleted successfully!');
    }
}