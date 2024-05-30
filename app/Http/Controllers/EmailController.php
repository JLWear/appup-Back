<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SendinBlueService;

class EmailController extends Controller
{
    protected $sendinBlueService;

    public function __construct(SendinBlueService $sendinBlueService)
    {
        $this->sendinBlueService = $sendinBlueService;
    }

    public function sendEmail(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
            'name' => 'required|string',
            'subject' => 'required|string',
            'message' => 'required|string',
        ]);

        try {
            $result = $this->sendinBlueService->sendEmail(
                'testynovv2@yopmail.com',
                'Test Nom',
                'Confirmation de paiement',
                'Bonjour'
            );
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
