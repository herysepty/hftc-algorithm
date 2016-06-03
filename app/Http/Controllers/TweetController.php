<?php
/*
    'select a.id,a.clear_tweet,b.tweet from clear_tweets a INNER JOIN tweets b  on a.id_tweet = b.id'
*/
namespace herysepty\Http\Controllers;
use DB;
use PDO;
use Illuminate\Http\Request;
use herysepty\Http\Requests; 
use Abraham\TwitterOAuth\TwitterOAuth; 
use herysepty\Libraries\Preprocessing;   
use herysepty\Libraries\Hftc; 
use herysepty\Http\Controllers\AppController; 
use Validator;     
use Session;  

class TweetController extends Controller
{
    public function __construct()
    {
        ini_set('max_execution_time', 36000);
        ini_set('memory_limit',-1);
        // $this->middleware('auth');
    }
    public function index()
    {
    	$tweets = DB::table('tweets')->orderBy('id','desc')->paginate(100);
    	$count = DB::table('tweets')->count();
		return view('contents.list_tweets')->with('tweets',$tweets)->with('count',$count);
    }
    public function formClassification()
    {
        return view('contents.form_classification');
    }
    public function viewClearTweet()
    {
        $tweets = DB::table('clear_tweets')
                    ->join('tweets','clear_tweets.id_tweet','=','tweets.id')
                    ->select('*')->paginate(20);
        return view('contents.list_clear_tweets')->with('tweets',$tweets);
    }

    public function getTweet(Request $request)
    {
        $post = $request->all();
        $date = date('Y-m-d',strtotime($post['date']));

		$consumer_key = "YyiX1I2pgTKaMAI4UbKxkFCJ0";
        $consumer_secret = "Kxw9IrUjFHz5IcVFhiBPUmjr1FxAvwSt3zveo2oPKqro1PMUni";
        $access_token = "715720484491399169-BRLVVh1oqYsy7Hq3bf1pRkWTfqCIHLc";
        $access_token_secret = "66q4RVaIq8NPHcc01mEaXXJXqPmLvqUfdtcbjuUvnBTHx";
        $twitter = new TwitterOAuth($consumer_key,$consumer_secret,$access_token,$access_token_secret);
        $h = 1;
        $keywords = array('bencana banjir','longsor','puting beliung','gempa','gunung meletus');
        foreach ($keywords as $value_keyword)
        {
            $flag = 1;
            $max_id = 0;
            for ($i=0; $i <999; $i++) 
            { 
                // $tweets = $twitter->get('https://api.twitter.com/1.1/search/tweets.json?q='.$value_keyword.'&result_type=recent&until='.$date.'&count=100&max_id='.$max_id);
                $tweets = $twitter->get("search/tweets", ["q" => $value_keyword,"until"=>$date,"count"=>100,"max_id"=>$max_id,"result_type"=>"recent"]);
                if(!empty($tweets->statuses))
                {
                    foreach ($tweets->statuses as $tweet)
                    {
                        if(date('Y-m-d',strtotime($tweet->created_at)) == date('Y-m-d',strtotime($date)-86400))
                        {
                            $max_id = $tweet->id_str;
                            $check_tweet = DB::table('tweets')->where('id_tweet' , $tweet->id_str)->count();
                            if($check_tweet == 0)
                            {
                                 $insert = DB::table('tweets')->insert(['id_tweet' => $tweet->id_str,'username' => $tweet->user->screen_name,'tweet' => $tweet->text,'date_tweet' => $tweet->created_at,'date_get_tweet' => date("Y-m-d H:i:s")]);
                            }
                        }
                        else
                        {
                            $flag = 0;
                        }
                    }
                }
                else
                {
                    break;
                }
                if($flag == 0)
                    break;
            }
        }
        $total_tweet = DB::table('tweets')->where('date_tweet','LIKE',date('D M d%Y',strtotime($date)-86400))->count();
        Session::flash('message',AppController::alertMessages('success',$total_tweet.' Tweet berhasil di tarik dari tweet',''));
        return redirect('tweet/view');
    }
    public function classification(Request $request)
    {
        $hftc = new Hftc();

        $post = $request->all();
        $v = Validator::make($post,
            [
                'minsupp' => 'required|numeric',
            ]);
        if($v->fails())
        {
            return redirect()->back()->withErrors($v->errors())->withInput();
        }
        else
        {
            $start_time = microtime(true);
            $h = $hftc->classification($post['minsupp']);
            $tweets = array();
            foreach ($h[2] as $key_terms => $value_tweets) {
                $tweet = array();
                foreach ($value_tweets as $id => $value_tw) 
                {
                    $tw = array();
                    $tw = DB::table('clear_tweets')
                            ->join('tweets','clear_tweets.id_tweet','=','tweets.id')
                            ->where('tweets.id',$id)
                            ->select('*')->first();
                    $tw = json_decode(json_encode($tw),true);
                    $tweet[] = $tw;
                    
                }
                $tweets[$key_terms] = $tweet;
            }
            $end_time = microtime(true);
            $long_time = $end_time-$start_time;
            // echo "<pre>";
            //     print_r($tweets);
            //     echo "</pre>";

            //     foreach ($h[2] as $key => $value) {
            //         echo count($value);
            //     }
            Session::flash('message',AppController::alertMessages('success','Hasil Klasifikasi',
                'Lama Waktu: <b>'.$long_time.
                '</b><br/>Jumlah terms: <b>'.count($h[0]).
                '</b><br/>Jumlah Cluster: <b>'.count($h[1]).
                '</b><br/>Jumlah Cluster Terakhir: <b>'.count($h[2]).
                '</b><br/>Level terakhir: <b>'.$h[3].'</b>'));
            return view('contents.result_classification')
                        ->with('time_agregation',$h[4])
                        ->with('tweets',$tweets)
                        ->with('level_max',$h[3])
                        ->with('last_clusters',$h[2])
                        ->with('clusters',$h[1])
                        ->with('terms',$h[0]);
        }
    }
    public function processingTweets(Request $request)
    {
        $p = new Preprocessing();
        $post = $request->all();
        $v = Validator::make($post,
            [
                'total_tweet' => 'required|numeric',
                'date_tweet' => 'required|date',
            ]);
        if($v->fails())
        {
            return redirect()->back()->withErrors($v->errors())->withInput();
        }
        else
        {
            $check_tweet = DB::table('tweets')->where('date_tweet','LIKE',date('D M d%Y',strtotime($post['date_tweet'])))->count();
            if($check_tweet>0)
            {
                DB::table('clear_tweets')->delete();
                DB::table('terms')->delete();
                $start_time_terms = microtime(true); 
                $terms = $p->preprocesing($post['date_tweet'],$post['total_tweet']);
                $end_time_terms = microtime(true);
                $long_time_processing = $end_time_terms-$start_time_terms;
                
                Session::flash('message',AppController::alertMessages('success','Berhasil Prosessing','Waktu Prosessing: '.$long_time_processing));
                return redirect('tweet/clear/view'); 
            }
            else
            {
                Session::flash('message',AppController::alertMessages('danger','Tweet tidak ada di tanggal '.$post['date_tweet'],'Silahkan tarik tweet, klik <a href="'.URL('tweet/get').'">disini</a> untuk tari tweet'));
                return redirect()->back();
            }
        }
    }
}