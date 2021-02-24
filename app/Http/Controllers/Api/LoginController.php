<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\users;
use \Firebase\JWT\JWT;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAccount()
    {
        return users::all();
    }

    public function loginUser(Request $request){
        $name = $request->input('account');
		$password = $request->input('password');   
        $key ="anh";
		if($name=="anhnguyen" && $password=="123456"){
            $_SESSION['name']=1;
            $cookie=Cookie::make('user', $name, 30);
            $array = array("idToken" => $name);
            return response()->json($array,200)->cookie($cookie);
            
		}else{
			$array = array("data" => null);
			return response()->json($array,400);
		}   
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $name = $request->input('account');
        $password = $request->input('password');
        $email= $request->input('useremail');
        $key ="anh";
        if (Auth::attempt(['account' => $name, 'password' => $password])) {
            $user_id= Auth::user()->id;
            // $data=JWT::encode($user_id, $key);
	 	    $array = array("idToken" => $user_id);
			return response()->json($array,400);
        }else{
            $users=new users();
            $users->id=4;
            $users->account=$request->get('account');
            $users->firstName='anh';
            $users->lastName='Nguyen';
            $users->email=$request->get('email');
            $users->phone=$request->get('phone');
            $users->gender=$request->get('gender');
            $users->address=$request->get('address');
            $users->password=Hash::make($request->get('password'));
            $users->birthday=date_create()->format('Y-m-d H:i:s');
            $users->remember_token='123';
            $users->save();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showAccount($id)
    {
        return users::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyAccount($id)
    {
        return users::destroy($id);
    }
}
