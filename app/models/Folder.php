<?php

class Folder extends Eloquent {

	protected $table = 'folders';
	public $timestamps = true;
	protected $softDelete = false;

	protected $fillable = ['name', 'slug', 'user_id'];

	public function user() {
		return $this->belongsTo('User');
	}

	public function bookmarks() {
		return $this->hasMany('Bookmark');
	}
}