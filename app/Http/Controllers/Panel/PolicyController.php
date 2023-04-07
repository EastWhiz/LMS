<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class PolicyController extends Controller
{

    function Index(){
        $user = auth()->user();
        $data['pageTitle'] = "Add Policy";
        $admin = DB::table("options")->where("option_key", "admin_policy")->where("add_by", 1)->first();
        $org = DB::table("options")->where("option_key", "org_policy")->where("add_by", $user->organ_id)->first();
        $data["AdminPolicy"] = $admin;
        $data["OrgPolicy"] = $org;
        return view(getTemplate() . '.panel.policy.index', $data);
    }

    function Add(){
        $request = Request();
        $user = auth()->user();
        $data = array();
        $data['pageTitle'] = "Add Policy";

        $key = "org_policy";
        $Policy = DB::table("options")->where("option_key", $key)->where("add_by", $user->id)->first();
        $data["Policy"] = $Policy;
        if ($request->has("submit")){
            $content = $request->message;
            $d = array(
                "option_key" => $key,
                "option_value" => $content,
                "add_by" => $user->id
            );
            if (isset($Policy->id)){
                DB::table("options")->where("id", $Policy->id)->update($d);
            }else{
                DB::table("options")->insert($d);
            }
            return redirect()->back();
        }

        return view(getTemplate() . '.panel.policy.add', $data);
    }
}