<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Page;

class PagesController extends Controller
{
    public function create(){
        if(request()->isMethod('post')){
            $this->validate(request(), [
                //'page_id' => 'required|integer',
                'title' => 'required|string|max:191',
                'description' => 'required|string',
                'content' => 'required|string',
                'image' => 'required|mimes:jpeg,bmp,png',
                'header' => 'required|in:0,1',
                'aside' => 'required|in:0,1',
                'footer' => 'required|in:0,1',
                'contact_form' => 'required|in:0,1',
                
            ]);
            
            $newRow = new Page();
            //$newRow->page_id = request('page_id');
            $newRow->title = request('title');
            $newRow->description = request('description');
            $newRow->content = request('content');
            $newRow->header = request('header');
            $newRow->aside = request('aside');
            $newRow->footer = request('footer');
            $newRow->contact_form = request('contact_form');
            $newRow->active = 0;
            $newRow->deleted = 0;
            
            $image = "";
           
            //check image element in request and accept image
             if(request()->hasFile('image')){
                $file = request('image');
//                echo $file->getClientOriginalName();
//                echo $file->getClientOriginalExtension();
                
                $fileName = str_slug($newRow->title, '-');
                $extension = $file->getClientOriginalExtension();
                $fullFileName = config('app.seo-image-prefix') . $fileName . "." . $extension;
                
                $file->move(public_path('/uploads/pages'), $fullFileName);
                $image = '/uploads/pages/' . $fullFileName;
                //die();
            }
            
            $newRow->image = $image;

            $newRow->save();
            
            // set message
            session()->flash('message-type', "success");
            session()->flash('message', "User $newRow->title has been created successfully!!!");
            
            
//            session()->flash('message', [
//                'type' => 'success',
//                'text' => "User $newRow->name has been created successfully!!!"
//            ]);
            
            return redirect( route('pages') );
        }
        
        return view('admin.pages.create');
    }
}
