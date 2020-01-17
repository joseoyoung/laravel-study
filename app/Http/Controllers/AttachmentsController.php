<?php

namespace App\Http\Controllers;

use App\Events\ModelChanged;
use Illuminate\Http\Request;
use App\Http\Requests;

class AttachmentsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // var_dump($request); exit;
        // return response()->json($request);
        
        if (! $request->hasFile('file')) {
            return response()->json('File not passed !', 422);
        }

        // Save file
        $file = $request->file('file');
        $name = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
        $file->move(attachment_path(), $name);

        $articleId = $request->input('articleId');

        // Persist Attachment model
        $attachment = $articleId
            ? \App\Article::findOrFail($articleId)->attachments()->create(['name' => $name])
            : \App\Attachment::create(['name' => $name]);

        event(new ModelChanged('attachments'));

        return response()->json([
            'id'   => $attachment->id,
            'name' => $name,
            'type' => $file->getClientMimeType(),
            'url'  => sprintf("/attachments/%s", $name),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int                     $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $attachment = \App\Attachment::findOrFail($id);
        $articleId = \App\Attachment::findOrFail($id)->article_id;
        // dd($articleId);exit;

        $path = attachment_path($attachment->name);
        if (\File::exists($path)) {
            \File::delete($path);
        }

        $attachment->delete();
        // event(new ModelChanged('attachments'));

        if ($request->ajax()) {
            return response()->json('', 204);
            return redirect('/articles/'.$articleId);
        }

        // dd($request);exit;
        // flash()->success(trans('common.deleted'));
       

        return redirect('/articles/'.$articleId);
    }

    // public function store2(Request $request)
    // {
    //     $path = $request->file('file')->getRealPath();
    //         $logo = file_get_contents($path);
    //         $base64 = base64_encode($logo);
    //         $account->logo = $base64;
    //         $account->save();
    //         return response('success');
    // }
}
