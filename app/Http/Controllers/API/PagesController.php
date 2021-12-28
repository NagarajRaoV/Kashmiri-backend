<?php

namespace App\Http\Controllers\API;

use App\HomepageSetting;
use App\Http\Controllers\Controller;
use App\Traits\HasBlog;
use Carbon\Carbon;
use Illuminate\Container\Container as App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Webkul\API\Http\Controllers\Shop\CustomerController;
use Webkul\API\Http\Resources\Customer\Customer as CustomerResource;
use Webkul\Customer\Mail\VerificationEmail;
use Webkul\Customer\Models\Customer;
use Illuminate\Support\Facades\Validator;
use Webkul\Product\Models\ProductFlat;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Sales\Models\Order;

class PagesController extends Controller
{
    use HasBlog;

    /**
     * ProductRepository object
     *
     * @var \Webkul\Product\Repositories\ProductRepository
     */
    protected $productRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Product\Repositories\ProductRepository $productRepository
     * @return void
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }


    public function updateHomeSettings(Request $request){

        $data = HomepageSetting::where('id',1)->first();

        if($data){

            $data->bannerImage = $request->bannerImage;
            $data->box1 = $request->box1;
            $data->box2 = $request->box2;
            $data->box3 = $request->box3;
            $data->box4 = $request->box4;
            $data->box5 = $request->box5;

            $data->file1 = $request->file1;
            $data->file2 = $request->file2;
            $data->file3 = $request->file3;
            $data->file4 = $request->file4;
            $data->file5 = $request->file5;

            $data->url1 = $request->url1;
            $data->url2 = $request->url2;
            $data->url3 = $request->url3;
            $data->url4 = $request->url4;
            $data->url5 = $request->url5;

            $data->headerTitle = $request->headerTitle;
            $data->subTitle = $request->subTitle;
            $data->linkcallout = $request->linkcallout;


            $data->productIds = $request->productIds;
            $data->save();

        } else {
            HomepageSetting::create([
                'bannerImage'       => $request->bannerImage,
                'data'       => $request->data,
                'productIds'       => $request->productIds,
                ]
            );

        }

        return $this->successResponse([
            'mgs'   =>  'Updated'
        ]);
    }
    public function getSearch(Request $request){
        if ($request->has('search')){
            $query = ProductFlat::query();

            $query->where('name','LIKE','%' . $request->search .  '%');

            $output = $query->get();

            if ($output){
                return $this->successResponse([
                    's' =>  $output
                ]);
            } else {
                return $this->errorResponse([
                    's'     =>  'no data'
                ]);
            }
        }
    }
    public function getUserConfig(Request $request){

        $orders = Order::where('customer_id', $request->user()->id)
            ->get();
    }

    public function Register(Request $request){
          try {

              \request()->validate( [
                  'first_name' => 'required',
                  'last_name' => 'required',
                  'email' => 'email|required|unique:customers,email',
                  'password' => 'confirmed|min:6|required',
              ]);

              $token = md5(uniqid(rand(), true));
              $data = [
                  'first_name' => $request->get('first_name'),
                  'last_name' => $request->get('last_name'),
                  'email' => $request->get('email'),
                  'password' => bcrypt($request->get('password')),
                  'channel_id' => core()->getCurrentChannel()->id,
                  'is_verified' => 0,
                  'customer_group_id' => 2,
                  'token' => $token
              ];

              Event::dispatch('customer.registration.before');

              $customer = Customer::create($data);

              Event::dispatch('customer.registration.after', $customer);

              $verificationData = [
                  'email' => $customer->email,
                  'token' => $token,
              ];

              Mail::queue(new VerificationEmail($verificationData));

              return $this->JwtKey($customer);

          } catch (\Exception $exception){
              return $this->errorResponse($exception->getMessage());
          }

    }

    public function VerfiyAccount($token){
        $customer = Customer::where('token', $token)->first();

        if ($customer) {
            $customer->update(['is_verified' => 1, 'token' => 'NULL']);

            return redirect()->to('https://dev.kashmiristores.com/email/verified');

        } else {
            return redirect()->to('https://dev.kashmiristores.com/invalid/link');
        }

    }
    public function LoginByGoogle(Request $request){

        try {
            $res = (new \GuzzleHttp\Client())->request('GET',
                'https://www.googleapis.com/oauth2/v3/tokeninfo', [
                    'query' => ['id_token' => $request->token],
                ]);
            $payload = json_decode($res->getBody()->getContents());


            $temp_password = \Illuminate\Support\Str::random(12);

            $customer_exist = Customer::where('email', $payload->email)->first();

            if($customer_exist){
                return   $this->JwtKey($customer_exist);
            }

            $request->replace(
                [
                    'email' => $payload->email,
                    'first_name' => $payload->given_name,
                    'last_name	' => $payload->family_name,
                    'password' => $temp_password,
                    'password_confirmation' => $temp_password,
                ]);

            $customer = Customer::create([
                'email'             => $payload->email,
                'first_name'        => $payload->given_name,
                'last_name'         => $payload->family_name,
                'status'            => 1,
                'is_verified'       => 1,
                'customer_group_id' => 2
            ]);
            return $this->JwtKey($customer);

        } catch (\Exception $exception){
            return $this->errorResponse('no dat');
        }
    }

    protected function JwtKey($customer){
        $jwtToken = null;

        if (! $jwtToken = auth()->guard('api')->login($customer)) {
            return response()->json([
                'error' => 'Invalid Email or Password',
            ], 401);
        }

        $customer = auth('api')->user();

        return response()->json([
            'token'   => $jwtToken,
            'message' => 'Logged in successfully.',
            'data'    => new CustomerResource($customer),
        ]);
    }
}
