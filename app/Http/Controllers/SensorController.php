<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Temperature;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SensorController extends Controller
{
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

            Temperature::create([
                'value' => $sensorData['temperatura']
            ]);

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

    /**
     * Funcion para obtener los datos de temperatura
     */
    public function getDataSumary()
    {
        $minAllowed = env('MIN_TEMPERATURE_ALLOWED', 45);
        $maxAllowed = env('MAX_TEMPERATURE_ALLOWED', 65);

        $maxTemp = Temperature::max('value');
        $minTemp = Temperature::min('value');

        $outOfRange = Temperature::where('value', '<', $minAllowed)
            ->orwhere('value', '>', $maxAllowed)
            ->get();
        
        $todayData = Temperature::whereDate('created_at', Carbon::today())->get();

        $averageTemp = $todayData->avg('value');
        
        return response()->json([
            'maxTemp' => $maxTemp,
            'minTemp' => $minTemp,
            'outOfRange' => $outOfRange->count(),
            'todayData' => $todayData,
            'averageTemp' => round($averageTemp, 2),
            'numberofdate' => $todayData->count(),
        ]);
    }
}
