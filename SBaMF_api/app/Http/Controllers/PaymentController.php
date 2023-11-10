<?php

namespace App\Http\Controllers;

use App\Models\Tickets;
use Illuminate\Http\Request;
use Mail;
use App\Mail\sendTicket;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Event;
use Illuminate\Mail\Events\MessageSent;

class PaymentController extends Controller
{
    public $amount;

    public function verify() {
        $res = json_decode($this->verify_payment(request('reference')));
        if($res->status) {
            $email = $res->data->customer->email;
            if ($res->data->amount == 4000 * 100) {
                $plan = "regular";
            } elseif ($res->data->amount == 19000 * 100) {
                $plan = "VIP";
            }
            elseif ($res->data->amount == 20000 * 100) {
                $plan = "bulk_reg";
            }
            $res = $this->generateTicket($email, $plan, request('reference'));
            if ($res) {
                return response()->json($res, $res['statusCode']);
            }
        } else {
            return response()->json([
                'status' => $res->status,
                'message' => $res->message
            ]);
        }
    }


    public function verify_payment($reference) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/transaction/verify/$reference",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer ". $_ENV["PAYSTACK_SECRET_KEY"],
                "Cache-Control: no-cache"
            )
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    public function generateTicket($email, $plan, $reference)
    {
        $ticketCount = Tickets::count();
        $lastTicket = null;
        $nextTicket = null;
        $res = null;

        if ($ticketCount > 0) {
            $lastTicket = Tickets::latest('id')->first();
        }

        if ($lastTicket && ($plan == 'regular' || $plan == 'VIP')) {
            $nextTicket = str_pad((int)$lastTicket->ticket + 1, 5, '0', STR_PAD_LEFT);
        } elseif ($lastTicket && $plan == "bulk_reg") {
            $res = $this->generateBulkTickets($email, $lastTicket, $reference);
        } elseif ($plan == 'regular' || $plan == 'VIP') {
            $nextTicket = '00001';
        } elseif ($plan == 'bulk_reg') {
            $res = $this->generateBulkTickets($email, null, $reference);
        }


        if($plan != 'bulk_reg') {
            $ticket = Tickets::create([
                'email' => $email,
                'ticket' => $nextTicket,
                'plan' => $plan,
                'reference' => $reference
            ]);

            if (!$ticket->save()) {
                return [
                    'status' => 'error',
                    'message' => 'Ticket storage failed',
                    'statusCode' => 500
                ];
            }

            $res = $this->sendEmail($email, $nextTicket, $plan, 1);
            if($res) {
                return [
                    'status' => 'success',
                    'message' => 'Tickets stored and sent successfully',
                    'ticket' => $nextTicket,
                    'plan' => $plan,
                    'statusCode' => 200
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Ticket not sent',
                    'ticket' => $nextTicket,
                    'plan' => $plan,
                    'statusCode' => 500
                ];
            }
        } elseif ($plan == 'bulk_reg') {
            return $res;
        }
    }

        private function generateBulkTickets($email, $lastTicket, $reference)
        {
            $data = [
                ['email' => $email, 'ticket' => str_pad((int)$lastTicket->ticket + 1, 5, '0', STR_PAD_LEFT), 'plan' => 'regular', 'reference' => $reference],
                ['email' => $email, 'ticket' => str_pad((int)$lastTicket->ticket + 2, 5, '0', STR_PAD_LEFT), 'plan' => 'regular', 'reference' => $reference],
                ['email' => $email, 'ticket' => str_pad((int)$lastTicket->ticket + 3, 5, '0', STR_PAD_LEFT), 'plan' => 'regular', 'reference' => $reference],
                ['email' => $email, 'ticket' => str_pad((int)$lastTicket->ticket + 4, 5, '0', STR_PAD_LEFT), 'plan' => 'regular', 'reference' => $reference],
                ['email' => $email, 'ticket' => str_pad((int)$lastTicket->ticket + 5, 5, '0', STR_PAD_LEFT), 'plan' => 'regular', 'reference' => $reference]
            ];

            $ticketArray = []; // Initialize an empty array to store ticket values

            foreach ($data as $item) {
                $ticketArray[] = $item['ticket'];
            }
            $result = Tickets::insert($data);
            if($result) {
                $res = $this->sendEmail($email, implode(', ', $ticketArray), "Regular", 5);
                if($res) {
                    return [
                        'status' => 'success',
                        'message' => 'Tickets stored and sent successfully',
                        'tickets' => implode(', ', $ticketArray),
                        'plan' => 'Bulk regular',
                        'statusCode' => 200
                    ];
                } else {
                    return [
                        'status' => 'error',
                        'message' => 'Ticket not sent',
                        'statusCode' => 500
                    ];
                }
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Ticket storage failed',
                    'statusCode' => 500
                ];
            }
        }

        public function sendEmail($email, $ticket, $plan, $num)
    {
        $success = true;

        $mailData = [
            'title' => 'Spirit, Body, and Mind Festival Ticket',
            'Event' => 'Spirit, Body, and Mind Festival',
            'Date' => '2nd December, 2023',
            'Time' => '8am',
            'Venue' => 'Genesis Wellness Park, 2 Wobo Street off Amaechi Drive, GRA phase 3',
            'Ticket_type' => $plan,
            'Number_of_tickets' => $num,
            'Ticket_id' => $ticket
        ];

        try {
            Mail::to($email)->send(new sendTicket($mailData));
        } catch (\Exception $e) {
            // Exception occurred during email sending
            // You can log the exception for further investigation
            // Log::error($e->getMessage());

            $success = false;
        }

        // Return the success flag
        return $success;
    }
}

