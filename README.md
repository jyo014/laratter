# 画像をツイートと一緒にツイート一覧に送信できる機能を作成した。

# 画像機能を追加する手順↓

1. <--マイグレーションの追加-->
   - ツイートテーブルに `image_path` カラムを追加するために、マイグレーションを作ってみた
   - マイグレーションの内容に `string('image_path')->nullable()` を追加
   - 実行はこんな感じで：
     ./vendor/bin/sail php artisan migrate
     一回この実行を行なっていたため
     ./vendor/bin/sail php artisan migrate:rollback --step=６
     を行い、データベースの方に再度追加した。

2. **モデルの設定**
   - `app/Models/Tweet.php` の `$fillable` に`image_path`を追加してみた
     php
     `protected $fillable = ['tweet', 'image_path'];`

3. **コントローラの修正**
   - `TweetController.php` の `store()` と `update()` に画像を保存する処理を追加した
     　php
     if ($request->hasFile('image')) {
         $path = $request->file('image')->store('tweets', 'public');
         $data['image_path'] = $path;
     }
     ```
   - これでツイートの作成や更新のときに画像も保存できるようになった感じ

4. **Bladeの修正**
   - 作成フォーム (`tweets/create.blade.php`) にファイル入力を追加した
     ```html
     <input type="file" name="image" accept="image/*">
     ```
   - `<form>` タグに `enctype="multipart/form-data"` を追加
   - 一覧画面 (`tweets/index.blade.php`) で画像を表示しta
     ```blade
     @if ($tweet->image_path)
       <img src="{{ asset('storage/' . $tweet->image_path) }}" alt="ツイート画像">
     @endif
     ```

5. **ストレージリンクの作成**
   
   ./vendor/bin/sail php artisan storage:link
   
   - これで `storage/app/public` にある画像をブラウザから見られるようにしてみた感じ

6. **確認**
   - ツイート作成画面で画像を添付して投稿できるようにしてみた
   - 一覧画面で画像がちゃんと表示されるのを確認した
   - 自分のプロフィールの方には機能をまだつけていないから、そ　っちにもつけれるようにする