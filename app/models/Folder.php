<?php

class Folder extends Eloquent {

	protected $table = 'folders';
	public $timestamps = true;
	protected $softDelete = false;

	public function user() {
		return $this->belongsTo('User');
	}

	public function bookmarks() {
		return $this->hasMany('Bookmark');
	}

	public static function findBySlug ($slug) {
		return self::whereSlug($slug)->first();
	}

}