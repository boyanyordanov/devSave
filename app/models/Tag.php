<?php

class Tag extends Eloquent {

	protected $table = 'tags';
	public $timestamps = true;
	protected $softDelete = false;

	public function user() {
		return $this->belongsTo('User');
	}

	public function bookmarks() {
		return $this->belongsToMany('Bookmark')->withTimestamps();
	}

}