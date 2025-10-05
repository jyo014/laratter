<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use Illuminate\Http\Request;

class TweetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
         $tweets = Tweet::with('user','liked')->latest()->get();
         return view('tweets.index', compact('tweets'));
    }
 

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('tweets.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // バリデーション
        $request->validate([
            'tweet' => 'required|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // データをまとめる
        $data = $request->only('tweet');

        // 画像がアップロードされた場合の処理
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('tweets', 'public');
            $data['image_path'] = $path;
        }

        // ユーザーに紐づけてツイートを保存
        $request->user()->tweets()->create($data);

        // 一覧ページへリダイレクト
        return redirect()->route('tweets.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tweet $tweet)
    {
        //
        $tweet->load('comments');
        return view('tweets.show', compact('tweet'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tweet $tweet)
    {
        return view('tweets.edit', compact('tweet'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tweet $tweet)
    {
        // バリデーション
        $request->validate([
            'tweet' => 'required|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // 更新データをまとめる
        $data = $request->only('tweet');

        // 画像がアップロードされている場合の処理
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('tweets', 'public');
            $data['image_path'] = $path;
        }

        // ツイートを更新
        $tweet->update($data);

        return redirect()->route('tweets.show', $tweet);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tweet $tweet)
    {
        //
        $tweet->delete();

    return redirect()->route('tweets.index');
    }
    /**
    * Search for tweets containing the keyword.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\View\View
    */
        public function search(Request $request)
    {

    $query = Tweet::query();

    // キーワードが指定されている場合のみ検索を実行
    if ($request->filled('keyword')) {
        $keyword = $request->keyword;
        $query->where('tweet', 'like', '%' . $keyword . '%');
    }

    // ページネーションを追加（1ページに10件表示）
    $tweets = $query
        ->latest()
        ->paginate(10);

    return view('tweets.search', compact('tweets'));
    }
}
