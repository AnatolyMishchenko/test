<?php

namespace App\Http\Controllers;

use App\Http\Resources\HistoryResource;
use App\Http\Requests\ParrotWingsRequest;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Models\History;

class ParrotWingsController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param ParrotWingsRequest $request
     * @return HistoryResource
     */
    public function givePW(ParrotWingsRequest $request)
    {
        $name = $request->get('name');
        $balance = $request->get('balance');
        $idFrom = Auth::user()->id;
        $from = DB::table('users')->where('id', $idFrom)->get();
        $to = DB::table('users')->where('name', $name)->get();
        foreach ($to as $toItem) {
            $nameTo = $toItem->name;
            $balanceTo = $toItem->balance;
            foreach ($from as $fromItem) {
                $balanceFrom = $fromItem->balance;
                if ($balanceFrom > $balance) {
                    DB::table('users')->where('name', $nameTo)->update(['balance' => $balanceTo + $balance]);
                    DB::table('users')->where('id', $idFrom)->update(['balance' => $balanceFrom - $balance]);
                    $data['name'] = $request->get('name');
                    $data['how'] = $request->get('balance');
                    $data['user_id'] = $idFrom;
                    $created = History::create($data);

                    return new HistoryResource($created);
                } else {
                    return response()->json(
                        [
                            'status' => 'Not enought money!',
                        ]);
                }
            }
        }
    }
}
