<?php

namespace App\Http\Controllers;

use App\CmsPage;
use Illuminate\Http\Request;

class CmsController extends Controller
{
    public function addCmsPage(Request $request)
    {
        if($request->isMethod('post')){
            
            $data = $request->all();
    		/*echo "<pre>"; print_r($data); die;*/
            if(empty($data['meta_title'])){
                $data['meta_title'] = "";    
            }
            if(empty($data['meta_description'])){
                $data['meta_description'] = "";    
            }
            if(empty($data['meta_keywords'])){
                $data['meta_keywords'] = "";    
            }

            $cmspage = new CmsPage;
    		$cmspage->title = $data['title'];
    		$cmspage->url = $data['url'];
            $cmspage->description = $data['description'];
            $cmspage->meta_title = $data['meta_title'];
            $cmspage->meta_description = $data['meta_description'];
    		$cmspage->meta_keywords = $data['meta_keywords'];
    		if(empty($data['status'])){
    			$status = 0;
    		}else{
    			$status = 1;
    		}
    		$cmspage->status = $status;
    		$cmspage->save();
    		return redirect()->back()->with('flash_message_success','CMS Page has been added successfully');

        }
        return view('admin.pages.add_cms_page');
    }

    public function viewCmsPages()
    {
        $cmsPages = CmsPage::get();
        return view('admin.pages.view_cms_pages')->with(compact('cmsPages'));
    }
}
