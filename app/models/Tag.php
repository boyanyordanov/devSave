<?php

class Tag extends Eloquent {

	protected $table = 'tags';
	public $timestamps = true;
	protected $softDelete = false;

  protected $fillable = ['name', 'slug', 'user_id'];

	public function user() {
		return $this->belongsTo('User');
	}

	public function bookmarks() {
		return $this->belongsToMany('Bookmark')->withTimestamps();
	}

}