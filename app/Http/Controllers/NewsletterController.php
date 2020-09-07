<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\NewsletterSubscriber;
use App\Exports\subscribersExport;
use Excel;



class NewsletterController extends Controller
{
    

    public function addSubscriber(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            /*echo "<pre>"; print_r($data); die;*/
            $subscriberCount = NewsletterSubscriber::where('email',$data['subscriber_email'])->count();
            if($subscriberCount>0){
                echo "exists";
            }else{
                // Add Newsletter Email in newsletter_subscribers table
                $newsletter = new NewsletterSubscriber;
                $newsletter->email = $data['subscriber_email'];
                $newsletter->status = 1;
                $newsletter->save();
                return redirect()->back();
            }
        }
    }

    public function viewNewsletterSubscribers(){
        $newsletters = NewsletterSubscriber::get();
        return view('admin.newsletters.view_newsletters')->with(compact('newsletters'));
    }

    public function deleteNewsletterEmail($id){
        NewsletterSubscriber::where('id',$id)->delete();
        return redirect()->back()->with('flash_message_success','Newsletter Email has been deleted!');
    }

    public function updateNewsletterStatus($id,$status){
        NewsletterSubscriber::where('id',$id)->update(['status'=>$status]);
        return redirect()->back()->with('flash_message_success','Newsletter Status has been updated!');
    }

    //  public function exportNewsletterEmails(){
    //   Excel::create('Export data', function($excel) {

    //         $excel->sheet('Sheet', function($sheet) {
    //         $data = NewsletterSubscriber::select('id','email','created_at')->where('status',1)->orderBy('id','Desc')->get();
      
    //          $sheet->fromArray($data);
    //         });
    //       })->download('xls');

    // }



    public function exportNewsletterEmails(){
        return Excel::download(new subscribersExport,'subscribers.xlsx');
    }

}
