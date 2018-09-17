<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    /**
     * Get the messages
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Get the users
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
