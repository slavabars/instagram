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
                if ($json->entry_data->ProfilePage[0]->graphql->user->edge_owner_to_timeline_media->edges ) {
                    foreach ($json->entry_data->ProfilePage[0]->graphql->user->edge_owner_to_timeline_media->edges  as $node) {
                        $node = $node->node;
                        $instagramm = \App\Instagram::find($node->id);
                        if (is_null($instagramm)) {
                            $instagramm = new \App\Instagram;
                        }
                        $instagramm->code = $node->shortcode;
                        $instagramm->date = $node->taken_at_timestamp;
                        //$instagramm->caption = count($node->edge_media_to_caption)>0 ? $node->edge_media_to_caption->edges[0]->node->text : '';
                        $instagramm->thumbnail_src = $node->thumbnail_src;
                        $instagramm->id = $node->id;
                        $instagramm->display_src = $node->display_url;
                        $instagramm->save();
                    }
                }
                //dd($htmla[1]);
            }
            return \App\Instagram::orderBy('id','desc')->take(12)->get();
        });
        return $instagramMegia;
    }

}
