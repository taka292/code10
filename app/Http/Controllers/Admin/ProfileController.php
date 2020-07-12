<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Profile;
use App\ProfileHistory;
use Carbon\Carbon;
class ProfileController extends Controller
{
    //
    public function add()
    {
        return view('admin.profile.create');
    }
    
public function create(Request $request)
  {   $this->validate($request, Profile::$rules);    $profile = new Profile;
      $form = $request->all();
unset($form['_token']);
      $profile->fill($form);
      $profile->save();
        return redirect('admin/profile/create');
    }
    public function index(Request $request)
  {
      $cond_title = $request->cond_name;
      if ($cond_title != '') {
          // 検索されたら検索結果を取得する
          $posts = Profile::where('name', $cond_title)->get();
      } else {
          // それ以外はすべてのニュースを取得する
          $posts = Profile::all();
      }
      return view('admin.Profile.index', ['posts' => $posts, 'cond_name' => $cond_name]);
  }
  
    
    public function edit(Request $request)
    {
        // News Modelからデータを取得する
      $profile = Profile::find($request->id);
      if (empty($profile)) {
        abort(404);    
      }
        return view('admin.profile.edit',['profile_form' => $profile]);
    }
    
    public function update(Request $request)
    {
         // Validationをかける
      $this->validate($request, Profile::$rules);
      // News Modelからデータを取得する
      $profile = Profile::find($request->id);
      // 送信されてきたフォームデータを格納する
      $profile_form = $request->all();
     
      unset($profile_form['_token']);
      // 該当するデータを上書きして保存する
      $profile->fill($profile_form)->save();
      
       $profileHistory = new ProfileHistory;
        $profileHistory->profile_id = $profile->id;
        $profileHistory->edited_at = Carbon::now();
        $profileHistory->save();

        return redirect('admin/profile/edit');
    }
}
