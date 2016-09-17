<?php
namespace herysepty\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use herysepty\Http\Requests; 
// use herysepty\Libraries\Twitter\twitteroauth;
use Abraham\TwitterOAuth\TwitterOAuth; 
use herysepty\Libraries\Preprocessing;   
use herysepty\Libraries\Hftc; 
use herysepty\Libraries\TimeAggregation; 
use herysepty\Libraries\Rank; 
use herysepty\Http\Controllers\AppController; 
use Validator;     
use Session;  
use Storage;
/*
    'select a.id,a.clear_tweet,b.tweet from clear_tweets a INNER JOIN tweets b  on a.id_tweet = b.id'
*/
class TweetController extends Controller
{
    public function __construct()
    {
        ini_set('max_execution_time', 36000);
        ini_set('memory_limit',-1);
        // $this->middleware('auth');
        AppController::hs();
    }
    public function index()
    {
        $tweets = DB::table('tweets')->orderBy('id','desc')->paginate(100);
        $count  = DB::table('tweets')->count();
        return view('contents.list_tweets')->with('tweets',$tweets)->with('count',$count);
    }
    public function h()
    {
        return view('contents.home');
    }
    public function viewClearTweet()
    {
        $tweets = DB::table('clear_tweets')->join('tweets','clear_tweets.id_tweet','=','tweets.id')->select('*')->paginate(20);
        return view('contents.list_tweet_preprocessing')->with('tweets',$tweets);
    }
    public function getTweet(Request $request)
    {
        $post                = $request->all();
        $date                = date('Y-m-d',strtotime($post['date']) + 86400);
        
        $consumer_key        = "YyiX1I2pgTKaMAI4UbKxkFCJ0";
        $consumer_secret     = "Kxw9IrUjFHz5IcVFhiBPUmjr1FxAvwSt3zveo2oPKqro1PMUni";
        $access_token        = "715720484491399169-BRLVVh1oqYsy7Hq3bf1pRkWTfqCIHLc";
        $access_token_secret = "66q4RVaIq8NPHcc01mEaXXJXqPmLvqUfdtcbjuUvnBTHx";
        $twitter             = new TwitterOAuth($consumer_key,$consumer_secret,$access_token,$access_token_secret);
        $twitter->setTimeouts(10, 360000);

        $keywords = array('bencana banjir','puting beliung','gempa bumi','gunung meletus','gelombang pasang','bencana kekeringan','bencana tsunami');
        foreach ($keywords as $value_keyword)
        {
            $flag = 1;
            $max_id = 0;
            for ($i=0; $i < 999; $i++) 
            { 
                $tweets = $twitter->get("search/tweets", ["q" => $value_keyword,"until"=>$date,"count"=>100,"max_id"=>$max_id,"result_type"=>"recent"]);
                if(!empty($tweets->statuses))
                {
                    foreach ($tweets->statuses as $tweet)
                    {
                        if(date('Y-m-d',strtotime($tweet->created_at)) == date('Y-m-d',strtotime($date)-86400))
                        {
                            $max_id      = $tweet->id_str;
                            $check_tweet = DB::table('tweets')->where('id_tweet' , $tweet->id_str)->count();
                            if($check_tweet == 0)
                            {
                                DB::table('tweets')->insert(['id_tweet' => $tweet->id_str,'username' => $tweet->user->screen_name,'tweet' => $tweet->text,'date_tweet' => $tweet->created_at,'date_get_tweet' => date("Y-m-d H:i:s")]);
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
                {
                    break;
                }
            }
        }
        $total_tweet    = DB::table('tweets')->where('date_tweet','LIKE',date('D M d%Y',strtotime($date)-86400))->count();
        if($total_tweet == 0)
        {
            Session::flash('message',AppController::alertMessages('warning','Tidak ada tweet yang terunduh',''));
            return redirect('tweet/view');
        }
        else
        {
            Session::flash('message',AppController::alertMessages('success','Tweet berhasil diunduh',''));
            return redirect('tweet/view');
        }
    }
    public function clustering(Request $request)
    {
        $hftc = new Hftc();
        $post = $request->all();
        $v = Validator::make($post,['minsupp' => 'required|numeric']);
        if($v->fails())
        {
            return redirect()->back()->withErrors($v->errors())->withInput();
        }
        else
        {
            $start_time = microtime(true);
            $h          = $hftc->clustering($post['minsupp']);
            $tweets     = array();
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

            $rank = Storage::get('public/rank_skor.json');

            $trend = $this->trending(json_decode($rank),$tweets);

            Session::flash('message',AppController::alertMessages('success','Hasil Klasterisasi',
                // 'Lama Waktu: <b>'.ceil($long_time).
                '<br/>Jumlah terms: <b>'.count($h[0]).
                '</b><br/>Jumlah Cluster : <b>'.count($h[1]).
                '</b><br/>Jumlah Cluster Terakhir : <b>'.count($h[2]).
                '</b><br/>Level terakhir : <b>'.$h[3].'</b>'));
            return view('contents.result_clustering')
                        ->with('tweets',$tweets)
                        ->with('level_max',$h[3])
                        ->with('last_clusters',$h[2])
                        ->with('clusters',$h[1])
                        ->with('terms',$h[0])
                        ->with('rank',json_decode($rank))
                        ->with('trend',array_reverse($trend));
        }
    }
    public function processingTweets(Request $request)
    {
        $p = new Preprocessing();
        $ta = new TimeAggregation();
        $r = new Rank();
        $post = $request->all();
        $v = Validator::make($post,
            [
                'date_tweet'       => 'required|date',
                'start_time_tweet' => 'required',
                'end_time_tweet'   => 'required',
            ]);
        if($v->fails())
        {
            return redirect()->back()->withErrors($v->errors())->withInput();
        }
        else
        {
            $check_tweet = DB::table('tweets')->where('date_tweet','LIKE',date('D M d%Y',strtotime($post['date_tweet'])))->count();

            if(strtotime($post['start_time_tweet']) > strtotime($post['end_time_tweet']))
            {
                Session::flash('message',AppController::alertMessages('danger','Jam Mulai harus lebih kecil dari jam akhir',''));
                return redirect()->back();
            }
            else if($check_tweet>0)
            {
                DB::table('clear_tweets')->delete();
                $start_time_terms     = microtime(true); 
                $terms                = $p->preprocesing($post['date_tweet'],$post['start_time_tweet'],$post['end_time_tweet']);
                $end_time_terms       = microtime(true);
                $long_time_processing = $end_time_terms-$start_time_terms;
                $start_date = date("Y-m-d H:i:s",strtotime($post['date_tweet']." ".$post['start_time_tweet']));
                $end_date = date("Y-m-d H:i:s",strtotime($post['date_tweet']." ".$post['end_time_tweet']));

                $time_aggregation = $ta->algorithmTimeAggregation($start_date,$end_date);
                Storage::put('public/time_aggregation.json', json_encode($time_aggregation));
                // Rank
                $rank = $r->Ranking($start_date,$end_date);
                Storage::put('public/rank_skor.json', json_encode($rank));

                $get_terms_all = Storage::get('public/terms.json');
                $get_clear_tweets = DB::table('clear_tweets')->get();

                $check_tweet = DB::table('clear_tweets')->count();
                if($check_tweet != 0)
                {
                    Session::flash('message',AppController::alertMessages('success','Berhasil Preproccesing', 'Tanggal : '.date('d-m-Y',strtotime($post['date_tweet'])).' Jam : '.date('H:i',strtotime($post['start_time_tweet'])).' sampai '.date('H:i',strtotime($post['end_time_tweet'])).'<br/>Minsupp minimum '.AppController::minsuppSuggestion()));
                    return view('contents.result_preprocessing')->with('time_aggregations',$time_aggregation)->with('terms',json_decode($get_terms_all))->with('clear_tweets',$get_clear_tweets);
                }
                else
                {
                    Session::flash('message',AppController::alertMessages('warning','Tidak ada tweet yang di processing',''));
                    return view('contents.form_processing');
                }
            }
            else
            {
                Session::flash('message',AppController::alertMessages('danger','Tweet di tanggal '.date('d-m-Y',strtotime($post['date_tweet'])).' belum diunduh','Silahkan unduh tweet, klik <a href="'.URL('tweet/get').'">disini</a> untuk unduh tweet'));
                return redirect()->back();
            }
        }
    }
    public function trending($rank,$cluster)
    {
        $tweets = array();
        foreach($rank as $r){
            foreach($r as $slot_time => $rank_terms){
                $tweet = array();
                foreach($rank_terms as $term => $skor)
                {
                    foreach($cluster as $key_last_cluster => $value_last_cluster)
                    {
                        $flag = 0;
                        foreach($value_last_cluster as $key_tweet => $value_tweet)
                        {
                            if(in_array($term,explode(" ",$value_tweet['clear_tweet'])))
                            {
                                $flag = 1;
                                break;   
                            }
                            else
                            {
                                break;
                            }
                        }
                        if($flag == 1){
                            $tweet[$term] = $value_last_cluster;
                            break;
                        }
                    }
                }
                $tweets[$slot_time] = $tweet;
            }
        }
        return $tweets;
    }
}