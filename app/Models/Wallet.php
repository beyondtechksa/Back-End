<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = ['balance', 'walletable_id', 'walletable_type','currency'];


    public function walletable(){
        return $this->morphTo();
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function credit($amount, $description = null)
    {
        $this->balance += $amount;
        $this->save();

        $this->transactions()->create([
            'type' => 'credit',
            'amount' => $amount,
            'description' => $description,
        ]);
    }

    public function debit($amount, $description = null)
    {

        $this->balance -= $amount;
        $this->save();

        $this->transactions()->create([
            'type' => 'debit',
            'amount' => $amount,
            'description' => $description,
        ]);
    }

}
