<?php

namespace App;

use Carbon\Carbon;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function incomes()
    {
        return $this->hasMany(Income::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function summary()
    {
        $this->income = number_format($this->incomes()
            ->where('date', Carbon::today())
            ->sum('amount'), 2);

        $this->expense = number_format($this->expenses()
            ->where('date', Carbon::today())
            ->sum('amount'), 2);

        $this->onhand = number_format($this->incomes()->sum('amount') - $this->expenses()->sum('amount'), 2);

        return $this;
    }
}
