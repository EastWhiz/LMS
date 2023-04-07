<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PolicyController extends Controller
{
    function Add(){
        $request = Request();
        $user = auth()->user();
        
        $key = "admin_policy";
        $Policy = DB::table("options")->where("option_key", $key)->where("add_by", 1)->first();
        $data["Policy"] = $Policy;
        if ($request->has("submit")){
            $content = $request->content;
            if ($content==""){
                $content = "&nbsp;";
            }
            $d = array(
                "option_key" => $key,
                "option_value" => $content,
                "add_by" => 1
            );
            if (isset($Policy->id)){
                DB::table("options")->where("id", $Policy->id)->update($d);
            }else{
                DB::table("options")->insert($d);
            }
            return redirect()->back();
        }

        $data = [
            'pageTitle' => "Set Policy",
            "Policy" => $Policy
        ];
        return view('admin.policy.add', $data);
    }
}