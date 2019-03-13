<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Model\File;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $files = File::all(); // 全データの取り出し
        return view('home', ["files" => $files]);
    }

    /**
     * ファイルアップロード処理
     */
    public function upload(Request $request)
    {
        $this->validate($request, [
            'file' => [
                // 必須
                'required',
                // アップロードされたファイルであること
                'file',
                // 画像ファイルであること
                'image',
                // MIMEタイプを指定
                'mimes:jpeg,png',
            ]
        ]);

        if ($request->file('file')->isValid([])) {
            $path = $request->file->store('public');
            File::insert(["path" => $path]);
            
            $files = File::all(); // 全データの取り出し
            return view('home', ["files" => $files])->with('filename', basename($path));
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors();
        }
    }
}