<?php
namespace herysepty\Http\Controllers;

use DB;
use Validator;
use Illuminate\Http\Request;
use herysepty\Http\Requests;
use herysepty\User;
use Session;
use Auth;
use Hash;
use herysepty\Http\Controllers\AppController as AppController;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
    	$users = DB::table('users')->paginate();
    	return view('contents.list_users')->with('users',$users);
    }
    public function form()
    {
    	return view('contents.user_form');
    }
    public function edit($id)
    {
        $user = User::where('id',$id)->first();
        if($user)
        {
            return view('contents.user_form')->with('user',$user);
        }
    }
    public function store(Request $requests)
    {
    	$post = $requests->all();
    	$validator = validator::make($post,
    		[
    			'name' => 'required|max:255',
                'email' => 'required|email|max:255|unique:users',
                'password' => 'required|confirmed|min:6',
    		]);
    	if($validator->fails()) 
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        else
        {
            $insert = User::create([
                    'name' => $post['name'],
                    'email' => $post['email'],
                    'password' => bcrypt($post['password']),
                ]);
            if($insert)
            {
                $requests->session()->flash('message','<div class="callout callout-success">
                            <h4><span class="fa fa-check-square-o"></span> User berhasil disimpan.</h4>
                            <p></p>
                          </div>');
                return redirect('user/view');
            }
            else
            {
                $requests->session()->flash('message','<div class="callout callout-danger">
                            <h4><span class="fa fa-minus-circle"></span>Kesalah dalam menyimpan.</h4>
                            <p></p>
                          </div>');
                return redirect()->back();
            }
        }
    }
    public function update(Request $request)
    {
        $post = $request->all();
        $validator = Validator::make($post,
            [
                'name' => 'required|max:255',
                'password' => 'required|confirmed|min:6',
                'old_password' => 'required',
            ]);
        if($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        else
        {
            $user = DB::table('users')->where('id',$post['id'])->first();
            if(Hash::check($post['old_password'],$user->password))
            {
                $update = User::where('id',$post['id'])->update([
                                                        'name' => $post['name'],
                                                        'password' => bcrypt($post['password']),
                                                        ]);
                if($update)
                {
                    Session::flash('message','<div class="callout callout-success">
                                    <h4><span class="fa fa-check-square-o"></span> User berhasil diubah.</h4>
                                    <p></p>
                                  </div>');
                    return redirect('user/view');
                }
                else
                {
                    Session::flash('message',AppController::alertMessages('danger','Terjadi kesalahan',''));
                    return redirect()->back();
                } 
            }
            else
            {
                Session::flash('message',AppController::alertMessages('danger','Kata sandi lama tidak sama',''));
                return redirect()->back();     
            }
        }
    }

    public function destroy($id)
    {
        $delete = User::where('id',$id)->delete();
        if($delete)
        {
            \Session::flash('message','<div class="callout callout-success">
                            <h4><span class="fa fa-check-square-o"></span> User berhasil dihapus.</h4>
                            <p></p>
                          </div>');
                return redirect('user/view');
        }
    }
}
