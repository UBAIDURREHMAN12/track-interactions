<?php

namespace App\Http\Controllers\Apis;

use App\Events\InteractionOccured;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Interaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InteractionController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function create(Request $request){
        $request->validate([
            'label' => 'required|string|min:5',
            'type' => 'required',
        ]);
        $inputs = $request->all();
        $inputs['user_id'] = Auth::id();
        $createInteraction = Interaction::create($inputs);

       if($createInteraction){

           // Fire the InteractionOccured event
           event(new InteractionOccured($inputs));

           return response()->json([
               'status' => 'success',
               'message' => 'Interaction created successfully',
               'interaction' => $createInteraction
           ]);
       }else{
           return response()->json([
               'status' => 'Fail',
               'message' => 'Something went wrong'
           ]);
       }

    }

    public function retrieve(Request $request){
        $request->validate([
            'id' => 'required|exists:interactions,id'
        ]);


        $interactionDetails = Interaction::find($request->id);

        if($interactionDetails){
            return response()->json([
                'status' => 'success',
                'message' => 'Interaction Detail',
                'interaction' => $interactionDetails
            ]);
        }else{
            return response()->json([
                'status' => 'Fail',
                'message' => 'Data not found against this interaction'
            ]);
        }

    }

    public function update(Request $request){
        $request->validate([
            'id' => 'required|exists:interactions,id',
            'label' => 'required|string|min:5',
            'type' => 'required',
        ]);
        $inputs = $request->all();
        $inputs['user_id'] = Auth::id();
        $interaction = Interaction::find($request->id);

        if($interaction){

            $interaction->update($inputs);
            $updatedData = Interaction::find($request->id);
            return response()->json([
                'status' => 'success',
                'message' => 'Interaction updated successfully',
                'interaction' => $updatedData
            ]);
        }else{
            return response()->json([
                'status' => 'Fail',
                'message' => 'Something went wrong'
            ]);
        }

    }

    public function delete(Request $request){
        $request->validate([
            'id' => 'required|exists:interactions,id'
        ]);

        $interaction = Interaction::find($request->id);

        if($interaction->delete()){

            return response()->json([
                'status' => 'success',
                'message' => 'Interaction deleted successfully',
            ]);
        }else{
            return response()->json([
                'status' => 'Fail',
                'message' => 'Something went wrong'
            ]);
        }

    }

    public function statistics(Request $request)
    {
        if(isset($request->start_date) && isset($request->end_date)){

            $statistics = DB::table('interactions')
                ->select('type', DB::raw('COUNT(*) as count'))
                ->groupBy('type')
                ->whereBetween('created_at', [$request->start_date, $request->end_date])
                ->get();

        }
        if(isset($request->start_date) && !isset($request->end_date)){

            $statistics = DB::table('interactions')
                ->select('type', DB::raw('COUNT(*) as count'))
                ->groupBy('type')
                ->whereDate('created_at', '>=', $request->start_date)
                ->get();

        }
        if(!isset($request->start_date) && !isset($request->end_date)){

            $statistics = DB::table('interactions')
                ->select('type', DB::raw('COUNT(*) as count'))
                ->groupBy('type')
                ->get();
        }



        return response()->json(['statistics' => $statistics]);
    }

}
