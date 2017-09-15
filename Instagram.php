<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Instagram extends Model {
    protected $table = 'instagram';

    public static function getInsta($name){
        $instagramMegia = \Cache::remember('instagramMegia'.$name, '60', function() use ($name) {
            $html = file_get_contents('https://www.instagram.com/'.$name.'/');
            preg_match('|<script type="text\/javascript">window._sharedData = (.*);<\/script>|isU',$html,$htmla);
            if(count($htmla[1])){
                $j=$htmla[1];
                $json = json_decode($j);
                if ($json->entry_data->ProfilePage[0]->user->media->nodes) {
                    foreach ($json->entry_data->ProfilePage[0]->user->media->nodes as $node) {
                        $instagram = \App\Instagram::find($node->id);
                        if (is_null($instagram)) {
                            $instagram = new \App\Instagram;
                        }
                        $instagram->code = $node->code;
                        $instagram->date = $node->date;
                        $instagram->caption = isset($node->caption) ? $node->caption : '';
                        $instagram->thumbnail_src = $node->thumbnail_src;
                        $instagram->id = $node->id;
                        $instagram->display_src = $node->display_src;
                        $instagram->save();
                    }
                }
            }
            return \App\Instagram::orderBy('id','desc')->take(12)->get();
        });
        return $instagramMegia;
    }

}