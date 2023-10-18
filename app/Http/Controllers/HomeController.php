<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class HomeController extends Controller
{
    private $post;
    private $user;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Post $post, User $user)
    {
        $this->post = $post;
        $this->user = $user;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $home_posts = $this->getHomePosts();
        $suggested_users = $this->getSuggestedUsers();

        return view('users.home')
                    ->with('home_posts', $home_posts)
                    ->with('suggested_users', $suggested_users);
    }

    #Get the posts of the users that the Auth user is following
    private function getHomePosts()
    {
        $all_posts = $this->post->latest()->get();
        $home_posts = []; // In case the $home_posts is empty, it will not return NULL, but empty instead

        foreach($all_posts as $post){
            if($post->user->isFollowed() || $post->user->id === Auth::user()->id){
                $home_posts[] = $post;
            }
        }

        return $home_posts;
    }

    #Get the users that the Auth user is not following
    private function getSuggestedUsers()
    {
        $all_users = $this->user->all()->except(Auth::user()->id);
        $suggested_users = [];

        foreach($all_users as $user){
            if(!$user->isFollowed()){
                $suggested_users[] = $user;
            }
        }
 
        // array_slice($array, $offset, $length, $preserve_keys)で構成。$array=新しく格納する配列名　$offset=抽出を開始する位置(0=頭から,-2=後ろ2つから)　$length=抽出する要素の数(必ず整数！もし負の要素を指定したらエラーまたは空の配列となる) $preserve_keys=元の配列のキーを保持するかどうか(default=連続した数値でキーが振り直される true=falseで元のキー保持)。$array, $offset以降は省略可。
        // $suggested_usersはarray_slice()関数によって抽出された要素を受け入れる新しい配列

        return array_slice($suggested_users, 0, 3);
    }

    public function search(Request $request)
    {
        $users = $this->user->where('name', 'like', '%' . $request->search . '%')->get();
        return view('users.search')->with('users', $users)->with('search', $request->search);
    }

}
