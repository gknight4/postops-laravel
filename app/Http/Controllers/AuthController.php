<?php
namespace App\Http\Controllers;

//use Illuminate\Http\Request;

use App\User;
use App\StringStore;

//use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use JWTAuth;
//use Tymon\JWTAuth\Contracts\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
//use Tymon\JWTAuth\JWTAuth;
use Validator, DB, Hash, Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Mail\Message;
//use Symfony\Component\HttpFoundation\Request;
use Request;


class AuthController extends Controller
{

    
    
    /**
     * API addStringStore
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addStringStore(Request $request)
    {
//               $auth = Request::header('Authorization');// to get a header
//               $token = substr($auth, 7) ;
//               Log::debug($token);
//               Log::debug(Request::get('password'));
//              Log::debug($request::all()[0]) ;
//              $oneStr = {text: "text", type: "type", userid: 11};
              $pay = JWTAuth::check(true);
              $sub = $pay['sub'] ;
//              $oneStr = ['text' => 'text', 'type' => 'text', 'userid' => 5] ;
//              $strings = $request::all();
//              $strings[0]['userid'] = $sub ;
//              StringStore::create($strings[0]) ;
//              StringStore::create($request::all()) ;
              foreach ($request::all() as $string){
                $string['userid'] = $sub ;
                StringStore::create($string) ;
              }
//               $strings = $request::all() ;
//               for ($i = 0 ; $i < count($strings) ; $i++) $strings[$i]['userid'] = $sub ;
//               StringStore::insert($strings) ;

//              $article = Article::create($request->all());

//        return response()->json($article);
              
              $pay = JWTAuth::check(true);
              $sub = $pay['sub'] ;
              Log::debug($sub);// userid
//              le ($pay);


//        return 
        $resp = response()->json(['result' => 'ok']);
        $this->addCors($resp);
        return $resp ;
    }

    /**
     * API addStringStore
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStringStore(Request $request)
    {
              $pay = JWTAuth::check(true);
              $sub = $pay['sub'] ;
//               Log::debug("Get StringStore");
//               Log::debug($sub);// userid
//              le ($pay);

//     public function index()
//     {
//         return Article::all();
//     }

//        return 
        $strings = StringStore::where('userid', $sub)->get(['type', 'text']);
//        $strings = StringStore::
//        Log::debug($strings->toArray());
        $resp = response()->json($strings);
        $this->addCors($resp);
        return $resp ;
    }

    /**
     * API Register
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)// , $useremail, $password
    {
//        $credentials = $request->only('password', 'useremail'); // 'name', 'email', 
//        Log::debug(Request::get('password')) ;
        
        $credentials = array('useremail' => Request::get('useremail'), 'password' => Request::get('password'));
        Log::debug($credentials);
//        return ;
//http://localhost:8000/open/users

        $rules = [
//            'name' => 'required|max:255',
            'useremail' => 'required|email|string|max:255|unique:users'
//            'email' => 'required|email|max:255|unique:users'
        ];

        $validator = Validator::make($credentials, $rules);
        Log::debug("validate");
        
        if($validator->fails()) {
            return response()->json(['result' => 'accountexists']);
        }

//         $name = $request->name;
//         $email = $request->email;
        $name = "none" ;
        $email = "none" ;
        $password = $credentials['password'];
        $useremail = $credentials['useremail'];

        $user = User::create(['name' => $name, 'email' => $email, 'useremail' => $useremail, 'password' => Hash::make($password), 'is_verified' => 1]);

        $verification_code = str_random(30); //Generate verification code

//        DB::table('user_verifications')->insert(['user_id'=>$user->id,'token'=>$verification_code]);

//         $subject = "Please verify your email address.";
//         Mail::send('email.verify', ['name' => $name, 'verification_code' => $verification_code],
//             function($mail) use ($email, $name, $subject){
//                 $mail->from(getenv('FROM_EMAIL_ADDRESS'), "From User/Company Name Goes Here");
//                 $mail->to($email, $name);
//                 $mail->subject($subject);
//             });

//        return 
///http://localhost:8000/open/users
// http://localhost:8000/open/users
        $resp = response()->json(['result' => 'ok']);
        $this->addCors($resp);
        return $resp ;
        
    }

    /**
     * API Verify User
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyUser($verification_code)
    {
        $check = DB::table('user_verifications')->where('token',$verification_code)->first();

        if(!is_null($check)){
            $user = User::find($check->user_id);

            if($user->is_verified == 1){
                return response()->json([
                                            'success'=> true,
                                            'message'=> 'Account already verified..'
                                        ]);
            }

            $user->update(['is_verified' => 1]);
            DB::table('user_verifications')->where('token',$verification_code)->delete();

            return response()->json([
                                        'success'=> true,
                                        'message'=> 'You have successfully verified your email address.'
                                    ]);
        }

        return response()->json(['success'=> false, 'error'=> "Verification code is invalid."]);

    }

    /**
     * API Login, on success return JWT Auth token
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login (Request $request, $useremail, $password)
    {
//         $credentials = $request->only('useremail', 'password');
        $credentials = array('useremail' => $useremail, 'password' => $password);
        $auth = Request::header('Content-type');// to get a header
        Log::debug("login");
        Log::debug($useremail . ", " . $password);
//        Log::debug($auth);
//        Log::debug($credentials);
//        Log::debug($user . ", " . $pass);
//http://localhost:8000/open/authenticate/a/3e23e8160039594a33894f6564e1b1348bbd7a0088d42c4acb73eeaed59c009d

        $rules = [
            'useremail' => 'required', // |email
            'password' => 'required',
        ];

        $validator = Validator::make($credentials, $rules);
        if($validator->fails()) {
            return response()->json(['success'=> false, 'error'=> $validator->messages()]);
        }

        $credentials['is_verified'] = 1;

        try {
            // attempt to verify the credentials and create a token for the user
            Log::debug("try") ;
            if (! $token = JWTAuth::attempt($credentials, ['this' => 'that'])) {
//                return 
                Log::debug("not") ;
                $resp = response()->json(['result' => 'nosuchuser'], 401);
                $this->addCors($resp);
                return $resp ;
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            Log::debug("catch") ;
            return response()->json(['result' => 'error'], 500);
        }

        // all good so return the token
        Log::debug("good") ;
        $resp = response()
        ->json(['result' => 'ok'])
        ->header('Authorization', 'Bearer ' . $token);
        $this->addCors($resp);
        return $resp ;
          
    }
    
    private function addCors($response){
      $response->header("Access-Control-Allow-Origin", "*")
      ->header("Access-Control-Allow-Headers", 
        "Accept, Accept-Encoding, Accept-Language, Authorization, Cache-Control, Connection, Content-Length, Content-Type, Host, Origin, Pragma, Referer, User-Agent")
      ->header("Access-Control-Expose-Headers", 
        "Accept, Accept-Encoding, Accept-Language, Authorization, Cache-Control, Connection, Content-Length, Content-Type, Host, Origin, Pragma, Referer, User-Agent")
      ->header("Access-Control-Allow-Methods", 
        "GET, POST, PUT, DELETE, PATCH") ;
    }
    
/*******************************************/
    /**
     * API Login, on success return JWT Auth token
     *
     */
    public function check(Request $request)// check if authorization token is still good
    {
        // all good so return the token
//         return 
        $resp = response()->json(['result' => 'ok']);
        $this->addCors($resp) ;
        return $resp ;
    }

/**********************************************/

    public function root(Request $request)
      { 
        $resp = response()->json(['result' => 'rootresponse']); 
        $this->addCors($resp) ;
        return $resp ;
      }

    public function options(Request $request)
      { 
        Log::debug("options");
        $resp = response()->json(['result' => 'rootresponse']);
        $this->addCors($resp);
        return $resp ;
      }
/*
func addCors (w http.ResponseWriter){
	->header("Access-Control-Allow-Origin", "*")	
	w.Header().Set("Access-Control-Allow-Headers", 
		"Accept, Accept-Encoding, Accept-Language, Authorization, Cache-Control, Connection, Content-Length, Content-Type, Host, Origin, Pragma, Referer, User-Agent")	
	w.Header().Set("Access-Control-Expose-Headers", 
		"Accept, Accept-Encoding, Accept-Language, Authorization, Cache-Control, Connection, Content-Length, Content-Type, Host, Origin, Pragma, Referer, User-Agent")	
	w.Header().Set("Access-Control-Allow-Methods", 
		"GET, POST, PUT, DELETE, PATCH")	
}

*/
      
    /**
     * Log out
     * Invalidate the token, so user cannot use it anymore
     * They have to relogin to get a new token
     *
     * @param Request $request
     */
    public function logout(Request $request) {
        $this->validate($request, ['token' => 'required']);

        try {
            JWTAuth::invalidate($request->input('token'));
            return response()->json(['success' => true, 'message'=> "You have successfully logged out."]);
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['success' => false, 'error' => 'Failed to logout, please try again.'], 500);
        }
    }

    /**
     * API Recover Password
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function recover(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            $error_message = "Your email address was not found.";
            return response()->json(['success' => false, 'error' => ['email'=> $error_message]], 401);
        }

        try {
            Password::sendResetLink($request->only('email'), function (Message $message) {
                $message->subject('Your Password Reset Link');
            });
        } catch (\Exception $e) {
            //Return with error
            $error_message = $e->getMessage();
            return response()->json(['success' => false, 'error' => $error_message], 401);
        }

        return response()->json([
            'success' => true, 'data'=> ['message'=> 'A reset email has been sent! Please check your email.']
        ]);
    }
}