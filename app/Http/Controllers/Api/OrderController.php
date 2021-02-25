<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\order;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getOrder()
    {
        return order::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function getAddProduct(Request $request)
    {
        $pro=DB::select('select id , quantity from orders where id_product ='.$request->get('id_pro').' and id_user='.$request->get('id_user'));
        if($pro==null){
            $order=new order();
            $order->id_product=$request->get('id_pro');
            $order->id_user=$request->get('id_user');
            $order->quantity=1;
            $order->save();
            echo "add new product sussess";
        }else{
            order::where("id", $pro[0]->id)->update([
              "quantity" =>$pro[0]->quantity+1
          ]);
          echo "increase quantity of product";
        }
    }

    public function getOrderDetails($id)
    {
        $review = DB::table('orders')
        ->join('product','orders.id','=','product.id')
        ->join('users','orders.id','=','users.id')
        ->select('product.*','users.account','orders.*')
        ->where('id_user',$id)
        ->get();
        return $review;
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
    public function destroy($id)
    {
        //
    }

    public function deleteProductInOrder(Request $request){
        $pro=DB::select('select id , quantity from orders where id_product ='.$request->get('id_pro').' and id_user='.$request->get('id_user'));
        if($pro[0]->quantity > 1){
            order::where("id", $pro[0]->id)->update([
                "quantity" =>$pro[0]->quantity-1
            ]);
            echo "decrease quantity of product";
        }else{
            DB::delete('delete from orders where id ='.$pro[0]->id);
            echo "delete product";
        }
    }
}
