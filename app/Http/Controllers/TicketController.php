<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TicketInstance;
use App\Models\TicketType;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Response;

class TicketController extends Controller
{

    public function updateTicketStock(Request $request, $ticketTypeId)
    {
        try {
            // Validate input if needed

            $ticketType = TicketType::findOrFail($ticketTypeId);
            $ticketType->stock = $request->input('new_stock_' . $ticketTypeId);
            $ticketType->save();

            return response()->json(['new_stock_' . $ticketTypeId => $ticketType->stock]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error updating stock: ' . $e->getMessage()], 500);
        }
    }

    public function myTickets(): View
    {
        $ticketInstances = TicketInstance::with(['order', 'ticketType.event'])
            ->whereHas('order', function ($query) {
                $query->where('buyer_id', Auth::user()->user_id);
            })->get();

        return view('pages.my_tickets', compact('ticketInstances'));
    }

    public function generateQRCode()
    {
        $url = route('ticket-verification', ['id' => $this->ticket_instance_id]);

        return QrCode::size(200)->generate($url);
    }

    public function verifyTicket($id)
    {
        $ticketInstance = TicketInstance::findOrFail($id);

        /*if ($ticketInstance->order->buyer_id == Auth::user()->user_id) {
            // Lógica de verificação bem-sucedida
            return "Bilhete válido!";
        } else {
            // Lógica de bilhete inválido
            return "Bilhete inválido.";
        }*/

        // Retorne a imagem do QR code como resposta
        return Response::make($this->generateQRCode($ticketInstance))->header('Content-Type', 'image/png');
    }

}
