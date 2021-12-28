<?php

namespace Webkul\API\Http\Controllers\Shop;

use App\NotifyEmail;
use App\Traits\HasBlog;
use Illuminate\Http\Request;

class CoreController extends Controller
{


    public function SendEmail(Request $request){

        if( $request->clientEmail != ''){

            $check_if = NotifyEmail::where('email', $request->clientEmail)->first();

            if($check_if){
                return [
                    'data' => [
                        'status'    =>  201
                    ]
                ];
            }

            NotifyEmail::create([
                'email' =>  $request->clientEmail
            ]);

            \Config::set('mail.driver', 'ses');
            \Config::set('mail.host', 'email-smtp.ap-south-1.amazonaws.com');
            \Config::set('mail.port', '587');
            \Config::set('mail.username', 'AKIAWNA2PIA3TLXN3JVR');
            \Config::set('mail.password', 'FKlVM2VvDnPsobIsZgUwpFPT3RXkvgMmvS8sOB6i');
            \Config::set('mail.encryption', 'tls');

            $html = 'test email';
            $to = $request->clientEmail;
            $subject = 'Greeting from Kashmir Store!';
            $from_email = 'noreply@my.sfwcs.network';
            $from_name = 'Kashmir Stores';

            $rr = substr("Tuesday",0,3);

            $status = \Mail::send("layouts.email", ['content' => $html], function ($message) use (
                $html,
                $to,
                $subject,
                $from_email,
                $from_name
            ) {
                $message->priority(1);
                $message->to($to);

                $message->from($from_email, $from_name);

                $message->subject($subject);
            });

        }

        return [
            'data' => [
                'status'    =>  200
            ]
        ];
    }
    /**
     * Returns a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getConfig()
    {
        $configValues = [];

        foreach (explode(',', request()->input('_config')) as $config) {
            $configValues[$config] = core()->getConfigData($config);
        }

        return response()->json([
            'data' => $configValues,
        ]);
    }

    /**
     * Returns a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCountryStateGroup()
    {
        return response()->json([
            'data' => core()->groupedStatesByCountries(),
        ]);
    }

    /**
     * Returns a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function switchCurrency()
    {
        return response()->json([]);
    }

    /**
     * Returns a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function switchLocale()
    {
        return response()->json([]);
    }
}
