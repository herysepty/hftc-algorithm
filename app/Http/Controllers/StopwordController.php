<?php
namespace herysepty\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use herysepty\Http\Requests;
use herysepty\Http\Controllers\AppController;
use Validator;
use Session;
class StopwordController extends Controller
{
    public function index()
    {
        $stopwords = DB::table('stopwords')
                        ->orderBy('stopword','ASC')
                        ->paginate(10);
        return view('contents.list_stopword')->with('stopwords',$stopwords);
    }
    public function store(Request $request)
    {
    	$post=$request->all();
    	$v = Validator::make($request->all(),
    			[
    				'stopword' => 'required',
    			]
    	);
		
		if($v->fails())
		{
			return redirect()->back()->withErrors($v->errors())->withInput();
		}
		else
		{
            $check = DB::table('stopwords')->where('stopword',strtolower($post['stopword']))->count();
            if($check>0)
            {
                Session::flash('message','<div class="callout callout-warning">
                            <h4><span class="fa fa-warning"></span> Stopword sudah ada.</h4>
                            <p></p>
                          </div>');
                return redirect()->back()->withInput();
            }
            else
            {
                $i = DB::table('stopwords')->insert(['stopword' => strtolower($post['stopword'])]);
                if($i>0)
                {
                    Session::flash('message','<div class="callout callout-success">
                                <h4><span class="fa fa-check-square-o"></span> Stopword berhasil disimpan.</h4>
                                <p></p>
                              </div>');
                    return redirect('stopword/view');
                }
                else
                {
                    Session::flash('message','<div class="callout callout-danger">
                                <h4><span class="fa fa-minus-circle"></span> Gagal disimpan.</h4>

                                <p></p>
                              </div>');
                    return redirect('stopword/add');
                }
            }
		}
    }
    public function edit($id)
    {
        $stopwords = DB::table('stopwords')
                        ->orderBy('stopword','ASC')
                        ->paginate(10);
        $stopword = DB::table('stopwords')->where('id',$id)->first();
        return view('contents.list_stopword')->with('stopword_edit',$stopword)->with('stopwords',$stopwords);
    }
    public function update(Request $request)
    {
        $post = $request->all();
        $v = Validator::make($post,
            [
                'stopword' => 'required'
            ]);
        if($v->fails())
        {
            return redirect()->back()->withErrors($v->errors())->withInput();
        }
        else
        {
            $update = DB::table('stopwords')->where('id',$post['id'])->update(['stopword'=>$post['stopword']]);
            Session::flash('message',AppController::alertMessages('success','Stopword berhasil di ubah',''));
            return redirect('stopword/view');
        }
    }
    public function destroy($id)
    {
        $delete = DB::table('stopwords')->where('id',$id)->delete();
        if($delete>0)
        {
            Session::flash('message',AppController::alertMessages('success','Stopword berhasil di hapus',''));
            return redirect('stopword/view');
        }
        else
        {
            Session::flash('message',AppController::alertMessages('danger','Terjadi kesalahan',''));
            return redirect('stopword/add');
        }
    }
    public function search(Request $request)
    {
        $post = $request->all();
        $validation = Validator::make($post,
            [
                'q' => 'required',
            ]);
        if($validation->fails())
        {
            return redirect()->back()->withErrors($validation->errors())->withInput();
        }
        else
        {
            $stopwords = DB::table('stopwords')->where('stopword','like','%'.$post['q'].'%')->paginate(10);
            if(count($stopwords) > 0)
            {
            Session::flash('message',AppController::alertMessages('success','Stopword berhasil di temukan',''));
                return view('contents.list_stopword')->with('stopwords',$stopwords)->with('q',$post['q']);
            }
            else
            {       
                Session::flash('message','<div class="callout callout-warning">
                                    <h4><span class="fa fa-check-square-o"></span> Stopword tidak ditemukan.</h4>
                                    <p></p>
                                  </div>');
                return redirect('stopword/view');       
            }
        }
    }
}
