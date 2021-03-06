<?php

namespace App\Modules\Dine\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Core\Http\Controllers\Core;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\Modules\Hangout\Models\Hangouts as HangoutsModel;
use Illuminate\Support\Facades\DB;
use App\Modules\Message\Models\Dine as DineModel;
use App\Modules\Hangout\Models\Recent_activity;
use App\Modules\Profile\Models\User_profile;
use App\Modules\Profile\Http\Controllers\Profile;

class Dine extends Controller {
    /*
     * 
     * function indexAction
     * 
     * Dine html view
     * param null
     */

    public function indexAction(Request $request, $token) {

        $userId = Core::decodeIdAction($token);
        $user = User::find($userId)->toArray();

        $allData = Profile::otherProfileViewAction($userId);

        return view('dine::index')->with(['user' => $user, 'token' => $token, 'fullData' => $allData['datas']]);
    }

    /*
     * 
     * function dineAction
     * 
     * dine sent Action
     * param null
     */

    public function dineSentAction(Request $request, $token) {

        $data = array(
            'receiver_id' => Core::decodeIdAction($token),
            'sender_id' => Auth::user()->id,
            'event' => strip_tags(input::get('event')),
            'location' => strip_tags(input::get('location')),
            'date' => strip_tags(input::get('date')),
            'time' => input::get('time'),
            'private_or_accompany' => input::get('private_accompany'),
            'family_member' => implode(',', input::get('family_member')),
        );
        $validator = Validator::make($data, [
                    'event' => 'required',
                    'location' => 'required',
                    'date' => 'required',
                    'time' => 'required',
                    'private_or_accompany' => 'required',
                    'family_member' => 'required',
        ]);
        $user = User::find(Auth::user()->id)->toArray();
        if ($validator->fails()) {

            foreach (array_values($validator->messages()->toArray()) as $msg) {
                $error = implode(' ', $msg) . '<br>';
            }
            return response()->json(['status' => 0, 'msg' => $error]);
        } else {

            $query = DineModel::create($data);
            if (!empty($query)) {
                Recent_activity::create(['user_id' => $data['sender_id'], 'receiver_id' => $data['receiver_id'], 'module_name' => 'dine', 'display_message' => 'You have a dining  request to']);
                return response()->json(['status' => 1]);
            }
            try {
                
            } catch (\PDOException $e) {

                $error = "Not sent dine message!";
                return response()->json(['status' => 0, 'msg' => $error]);
            } catch (\Exception $e) {
                $error = "Not sent dine message!";
                return response()->json(['status' => 0, 'msg' => $error]);
            }
        }
    }

    /*
     * 
     * function dineListAction
     * 
     * dine List Action
     * param null
     */

    public function dineListAction() {
        $userId = Auth::User()->id;
        $dines = DineModel::select("dines.*", "users.name", 'users.profileimage')
                        ->join('users', function($sql) use($userId) {
                            $sql->on('users.id', 'dines.receiver_id');
                            $sql->where('dines.sender_id', $userId);
                        })
                        ->distinct('id')
                        ->union(DineModel::select("dines.*", "users.name", 'users.profileimage')
                                ->join('users', function($sql) use($userId) {
                                    $sql->on('users.id', 'dines.sender_id');
                                    $sql->where('dines.receiver_id', $userId);
                                })
                                ->distinct('id')
                                ->orderBY('dines.id', 'desc'))->orderBY('id', 'desc')->get()->toArray();

        return view('dine::dineList')->with(['dines' => $dines, 'token' => Core::encodeIdAction($userId)]);
    }

}
