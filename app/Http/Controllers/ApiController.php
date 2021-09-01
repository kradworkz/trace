<?php

namespace App\Http\Controllers;

use DB;
use PDF;
use Auth;
use View;
use Input;
use Carbon;
use Request;
use Session;
use App\Models\User;
use App\Models\Group;
use App\Models\Action;
use App\Models\Comment;
use App\Models\Company;
use App\Models\Document;
use App\Models\UserRight;
use App\Models\ActionDone;
use App\Models\GroupMember;
use App\Models\DCommentSeen;
use App\Models\DocumentType;
use App\Models\UserGroupRight;
use App\Models\DocumentRouting;
use App\Models\DocumentAttachment;

use App\Models\Region;
use App\Models\UserLog;

/*Added models access by jcda - to be use in dost6info API*/
use App\Models\ECommentSeen;
use App\Models\EventSeen;
use App\Models\Meeting;
use App\Models\Participant;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
   /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function index()
   {
      //
   }

   public function pending_for_dost6info($username)
   {
      $userid = DB::table('t_users')->select('*')->where('u_username', '=', $username)->first();
      $u_id = $userid->u_id;
      $ug_id = $userid->ug_id;

      $unrouted = 0;
      $pending = 0;
      $ypending = 0;
      $d_comm = 0;
      $e_comm = 0;
      $incoming = 0;
      $outgoing = 0;
      $events = 0;
      $meetings = 0;
      $unapproved = 0;
      $test = 999;

      $ur_id          = UserRight::where('ur_name', 'Incoming Documents')->pluck('ur_id');
      $ugr_add        = UserGroupRight::where('ug_id', $ug_id)->where('ur_id', $ur_id)->pluck('ugr_add');

      if ($ugr_add == 1) { //RD, DC, Superadmin
         $unrouted     = Document::where('d_status', 'Incoming')->where('d_routingthru', 0)->count();
         $pending      = Document::where('d_istrack', 1)->where('d_iscompleted', 0)->count();
         $ypending     = DocumentRouting::where('u_id', $u_id)->where('dr_completed', 0)->count();

         if ($ug_id == 1) {
            $d_comm     = Comment::where('comm_document', 1)->where('comm_rd', 0)->count();
            $e_comm     = Comment::where('comm_event', 1)->where('comm_event', 0)->count();
         } else if ($ug_id == 3 || $ug_id == 5) {
            $d_comm     = DCommentSeen::where('u_id', $u_id)->where('dcs_seen', 0)->count();
            $e_comm     = ECommentSeen::where('u_id', $u_id)->where('ecs_seen', 0)->count();
         }

         $incoming     = DocumentRouting::where('u_id', $u_id)->where('updated_at', '0000-00-00 00:00:00')->where('dr_status', 1)->where('dr_seen', 0)->count();
         $outgoing     = DocumentRouting::where('u_id', $u_id)->where('dr_status', 0)->where('dr_seen', 0)->count();
         $events       = EventSeen::where('u_id', $u_id)->where('es_seen', 0)->where('es_invited', 1)->count();
         $meetings     = Meeting::where('m_status', 'Pending')->where('m_datechecked', '0000-00-00')->count();

         if ($ug_id == 1) {
            $actions    = ActionDone::where('ad_rd', 0)->count();
         } else {
            $actions    = ActionDone::where('ad_seen', 0)->count();
         }

         $unapproved   = User::where('u_active', 0)->count();
         //return $ypending;

         // $total        = $unrouted + $pending + $ypending + $d_comm + $e_comm + $incoming + $outgoing + $events + $meetings + $unapproved + $actions;
      } else { //return "Division Chief, Ordinary User";
         $unrouted     = 0;
         $ypending     = 0;
         $pending      = DocumentRouting::where('u_id', $u_id)->where('dr_completed', 0)->where('dr_status', 1)->count();
         $d_comm       = DCommentSeen::where('u_id', $u_id)->where('dcs_seen', 0)->count();
         $e_comm       = ECommentSeen::where('u_id', $u_id)->where('ecs_seen', 0)->count();
         //$incoming   = DocumentRouting::where('u_id', $u_id)->where('updated_at', '0000-00-00 00:00:00')->where('dr_completed', 1)->where('dr_seen', 0)->count();
         //$incoming     = DB::table('t_documents')->join('t_document_routings', 't_documents.d_id', '=', 't_document_routings.d_id')->where('t_document_routings.u_id', $u_id)->where('t_document_routings.updated_at', '0000-00-00 00:00:00')->where('t_documents.d_status', 'Incoming')->where('t_document_routings.dr_seen', 0)->count();
         $incoming     = DB::table('t_documents')->join('t_document_routings', 't_documents.d_id', '=', 't_document_routings.d_id')->where('t_document_routings.u_id', $u_id)->where('t_documents.d_status', 'Incoming')->where('t_document_routings.dr_seen', 0)->count();
         //$incoming  = DocumentRouting::where('u_id', $u_id)->where('updated_at', '0000-00-00 00:00:00')->where('dr_status', 1)->where('dr_seen', 0)->count();
         $outgoing     = DB::table('t_documents')->join('t_document_routings', 't_documents.d_id', '=', 't_document_routings.d_id')->where('t_document_routings.u_id', $u_id)->where('t_document_routings.updated_at', '0000-00-00 00:00:00')
            ->where('t_documents.d_status', 'Outgoing')->where('t_document_routings.dr_seen', 0)->count();
         $events       = EventSeen::where('u_id', $u_id)->where('es_seen', 0)->where('es_invited', 1)->count();
         $meetings     = Participant::where('u_id', $u_id)->where('p_seen', 0)->count();
         //if($ug_id == 2 || $u_administrator == 1) {
         //$unapproved = User::where('u_active', 0)->count();
         //} else {
         $unapproved = 0;
         // }

         // $total        = $unrouted + $pending + $d_comm + $e_comm + $incoming + $outgoing + $events + $meetings + $unapproved;
      }
      $total        = $unrouted + $pending + $ypending + $d_comm + $e_comm + $incoming + $outgoing + $events + $meetings + $unapproved;
      return $total;
      //return $incoming;


      /* //Test query
                $documents  = DB::table('t_documents')->join('t_document_routings', 't_documents.d_id', '=', 't_document_routings.d_id')->where('t_documents.d_status', 'Incoming')
                            ->where('t_document_routings.u_id', $id)->where('t_document_routings.dr_completed', 0)->orderBy('t_document_routings.created_at', 'desc')
                            ->orderBy('t_document_routings.dr_seen', 'asc')->get();
                */
      //$incoming_total = DB::table('t_document_routings')->select('*')->where('u_id','=',$id)->get();

      //Used same codes in main_header.blade.php, except for u_id and ug_id which was determined by GET data from routes
      /*
                $unrouted     = Document::where('d_status', 'Incoming')->where('d_routingthru', 0)->count();
                $pending      = Document::where('d_istrack', 1)->where('d_iscompleted', 0)->count();
                $ypending     = DocumentRouting::where('u_id', $id)->where('dr_completed', 0)->count();

                //if($ug_id == 1) {
                    $d_comm     = Comment::where('comm_document', 1)->where('comm_rd', 0)->count();
                    $e_comm     = Comment::where('comm_event', 1)->where('comm_event', 0)->count();
                /*} elseif($ug_id == 3 || $ug_id == 5) {
                    $d_comm     = DCommentSeen::where('u_id', $id)->where('dcs_seen', 0)->count();
                    $e_comm     = ECommentSeen::where('u_id', $id)->where('ecs_seen', 0)->count();
                }

                $incoming     = DocumentRouting::where('u_id', $id)->where('updated_at', NULL)->where('dr_status', 1)->where('dr_seen', 0)->count();
                $outgoing     = DocumentRouting::where('u_id', $id)->where('dr_status', 0)->where('dr_seen', 0)->count();
                $events       = EventSeen::where('u_id', $id)->where('es_seen', 0)->where('es_invited', 1)->count();
                $meetings     = Meeting::where('m_status', 'Pending')->where('m_datechecked', '0000-00-00')->count();

                if($ug_id== 1) {
                    $actions    = ActionDone::where('ad_rd', 0)->count();
                } else {
                    $actions    = ActionDone::where('ad_seen', 0)->count();
                }
                $unapproved   = User::where('u_active', 0)->count();
                $total        = $unrouted + $pending + $ypending + $d_comm + $e_comm + $incoming + $outgoing + $events + $meetings + $unapproved + $actions;
        //return view('incoming.pending', compact('search', 'documents'));
        //return 'Testsss: '.$documents;
        //echo '<pre>';
        //$total_pending = count($incoming_total);
        //print_r($documents);
        return $total; */
   }



   public function handleLoginForDost6($username, $password)
   {
      $userdata = [
         'u_username' => $username,
         'password'  => $password,
         'u_active'  => 1
      ];

      if (Auth::attempt($userdata)) {
         $ul               = new UserLog;
         $ul->u_id = $uid = Auth::id();
         //$ul->ul_ip        = $request->getClientIp();
         //$ul->ul_session   = Session::getId();
         //$ul->created_at   = Carbon\Carbon::now();
         //$ul->save();

         $userdata = DB::table('t_users')->select('*')->where('u_id', '=', $uid)->first();
         $username = $userdata->u_username;
         $fname = $userdata->u_fname;
         $lname = $userdata->u_lname;
         $uemail = $userdata->u_email;
         $arrayt = array('id' => Auth::id(), 'username' => $username, 'name' => $fname . ' ' . $lname, 'email' => $uemail);

         return $arrayt;
         //echo 'logged';
      } else {
         echo 'Invalid username or password.';
      }
      //return redirect('/')->with('error_message', 'Invalid username or password.');

   }   
   public function handleLoginForDost6Encrypted($username, $password)
   {
      $userdata = [
         'u_username' => $username,
         'password'  => $this->my_simple_crypt($password),
         'u_active'  => 1
      ];

      if (Auth::attempt($userdata)) {
         $ul               = new UserLog;
         $ul->u_id = $uid = Auth::id();
         //$ul->ul_ip        = $request->getClientIp();
         //$ul->ul_session   = Session::getId();
         //$ul->created_at   = Carbon\Carbon::now();
         //$ul->save();

         $userdata = DB::table('t_users')->select('*')->where('u_id', '=', $uid)->first();
         $username = $userdata->u_username;
         $fname = $userdata->u_fname;
         $lname = $userdata->u_lname;
         $uemail = $userdata->u_email;
         $arrayt = array('id' => Auth::id(), 'username' => $username, 'name' => $fname . ' ' . $lname, 'email' => $uemail);

         return $arrayt;
         //echo 'logged';
      } else {
         echo 'Invalid username or password.';
      }
      //return redirect('/')->with('error_message', 'Invalid username or password.');

   }   



    public function autoLoginForDost6($username,$password){    
        $userdata = [   
                    'u_username'=> $username,
                    'password'  => $this->my_simple_crypt($password),
                    'u_active'  => 1  ];      


        if(Auth::attempt($userdata)) {
          $ul               = new UserLog;
          $ul->u_id         = Auth::id();
          //$ul->ul_ip        = $request->getClientIp();
          $ul->ul_session   = Session::getId();
          $ul->created_at   = Carbon\Carbon::now();
          $ul->save();
          //return redirect('dashboard');
          return redirect('dashboard');
        }else{
           return redirect('/')->with('error_message', 'Invalid username or password.');
        }
        //return redirect('/')->with('error_message', 'Invalid username or password.');

    }

  
    function my_simple_crypt( $string, $action = 'd' ) {
        // you may change these values to your own
        $secret_key = 'kyahwel';
        $secret_iv = 'wilkuya';
     
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $key = hash( 'sha256', $secret_key );
        $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );
     
        if( $action == 'e' ) {
            $output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
        }
        else if( $action == 'd' ){
            $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
        }
      //echo $output;
      return $output;
    }
    
   public function getAllUsersForDost6()
   {
      $userdata = DB::table('t_users')->get();
      return $userdata;
      //$arrayt = array('id' => Auth::id(),'username'=>$username,'name'=>$fname.' '.$lname);
      //return $arrayt;
   }

   public function getAllActiveUsersForDost6()
   {
      $userdata = DB::table('t_users')->where('u_active', 1)->get();
      return $userdata;
      //$arrayt = array('id' => Auth::id(),'username'=>$username,'name'=>$fname.' '.$lname);
      //return $arrayt;
   }

   /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function create()
   {
      //
   }

   /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function store(Request $request)
   {
      //
   }

   /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function show($id)
   {
      //
   }

   /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function edit($id)
   {
      //
   }

   /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function update(Request $request, $id)
   {
      //
   }

   /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function destroy($id)
   {
      //
   }
}
