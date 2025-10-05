<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SensorController extends Controller
{
    // public function test()
    // {
    //     broadcast(new MessageSent('hello word', 'hola'));

    //     return response()->json([
    //         'message' => 'success'
    //     ]);
    // }

    /**
     * 
     * Funcion para encender o apagar el sensor
     */
    public function switchTurn(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'estado' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'data not validat'
            ]);
        }

        $response = Http::post(env('URL_SENSOR'), [
            'estado' => $request->estado,
        ]);

        return response()->json([
            'message' => $response,

        ]);
    }

    /**
     * Store almacena y ejecuta el broadcast del socket
     */
    public function store(Request $request) : JsonResponse
    {
        try {

            $sensorData = $request->all();
            broadcast(new MessageSent($sensorData['temperatura'], $sensorData['timestamp']));

            return response()->json([
                'message' => 'success'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status'=> 'error',
                'message'=> $e->getMessage()
            ]);
        }
    }
}
