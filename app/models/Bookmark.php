<?php

class Bookmark extends Eloquent {

	protected $table = 'bookmarks';
	public $timestamps = true;
	protected $softDelete = false;

	protected $fillable = ['title', 'url', 'notes', 'folder_id'];

	public function folder() {
		return $this->belongsTo('Folder');
	}

	public function tags() {
		return $this->belongsToMany('Tag')->withTimestamps();
	}

	public function user () {
		return $this->belongsTo('User');
	}

}