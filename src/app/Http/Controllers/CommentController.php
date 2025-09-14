<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CommentRequest;
use App\Models\Product;
use App\Models\ProductComment;

class CommentController extends Controller
{
    public function store(CommentRequest $request, Product $product)
    {
        ProductComment::create([
            'product_id' => $product->id,
            'user_id' => Auth::id(),
            'body' => $request->body,
        ]);

        return back()->with('message', 'コメントを投稿しました')->withFragment('comment-form');
    }
}
