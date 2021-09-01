<?php

namespace App\Http\Controllers;

use Auth;
use View;
use Carbon;
use App\Models\Comment;
use App\Models\ECommentSeen;
use App\Models\DCommentSeen;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class T_CommentController extends Controller
{
    public function __construct() {
        $data = [ 'page' => 'Comments' ];
        View::share('data', $data);
    }

    public function documentIndex() {    
        $comments = DCommentSeen::where('u_id', Auth::user()->u_id)->where('dcs_seen', 0)->groupBy('d_id')->Paginate(20);    
        return view('comments.documents', compact('comments'));
    }

    public function eventIndex() {        
        $comments = ECommentSeen::where('u_id', Auth::user()->u_id)->where('ecs_seen', 0)->groupBy('e_id')->Paginate(20);    
        return view('comments.events', compact('comments'));
    }

    public function documentRead() {
        DCommentSeen::where('u_id', Auth::user()->u_id)->where('dcs_seen', 0)->update(['dcs_seen'=>1, 'updated_at'=>Carbon\Carbon::now()]);
        return redirect('dashboard')->with('success', 'Comments marked as read.');
    }

    public function eventRead() {
        ECommentSeen::where('u_id', Auth::user()->u_id)->where('ecs_seen', 0)->update(['ecs_seen'=>1, 'updated_at'=>Carbon\Carbon::now()]);
        return redirect('dashboard')->with('success', 'Comments marked as read.');
    }
}
