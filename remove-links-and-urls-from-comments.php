<?php
/*
Plugin Name: Remove links and URLs from comment text
Plugin URI: https://marc.tv
Description: A simple plugin that removes all urls from Wordpress comments after 30 days.
Version: 1.9
Author: MarcDK
Author URI: https://marc.tv
GitHub Plugin URI: mtoensing/remove-links-and-urls-from-comments
*/

const DELAY = '-30 days';

function marctv_filter_text($comment_text, $comment = null)
{
    if ($comment !== null) {
        $commentdate = strtotime($comment->comment_date);

        if ($commentdate < strtotime(DELAY)) {
            $link_pattern = "/<a[^>]*>(.*)<\/a>/iU";
            $comment_text = preg_replace($link_pattern, "$1", $comment_text);
            $comment_text = preg_replace('/\b((https?|ftp|file):\/\/|www\.)[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i', ' ', $comment_text);
        }
    }
    return $comment_text;
}


function marctv_remove_comment_author_link($comment_author_link, $author, $comment_ID)
{
    $comment     = get_comment($comment_ID);
    $commentdate = strtotime($comment->comment_date);

    if (is_single() and $commentdate < strtotime(DELAY)) {
        return $author;
    } else {
        return $comment_author_link;
    }
}

function marctv_remove_comment_author_url($url, $comment_ID, $comment)
{
    $commentdate = strtotime($comment->comment_date);

    if (is_single() and $commentdate < strtotime(DELAY)) {
        return '';
    }
    return $url;
}

add_filter('get_comment_author_url', 'marctv_remove_comment_author_url', 10, 3);
add_filter('comment_text', 'marctv_filter_text', 10, 2);
add_filter('get_comment_author_link', 'marctv_remove_comment_author_link', 10, 3);
