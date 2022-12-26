<?php

namespace Modules\Notification\Entities;

use App\Models\Candidates\Candidate;
use App\Models\Employer\Employer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Notification\Jobs\SendMail;

class Notification extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'send_by',
        'send_to',
        'send_to_model_type',
        'send_by_model_type',
        'is_read',
        'status',
        'type'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];
    
    const STATUS_PENDING = 1;
    const STATUS_QUEUE = 2;
    const STATUS_SENT = 3;

    const TYPE_MAIL = 1;
    const TYPE_PUSH_NOTIFICATION = 2;

    public function getStatusList()
    {
       return [
          self::STATUS_PENDING => 'Pending',
          self::STATUS_QUEUE => 'In Queue',
          self::STATUS_SENT => 'Sent'
       ];
    }

    public function getTypeList()
    {
       return [
          self::TYPE_MAIL => 'Mail',
          self::TYPE_PUSH_NOTIFICATION => 'Push Notification'
       ];
    }

    public function getStatuAttribute($value)
    {
        $statusType = $this->getStatusList();

        return !empty($statusType[$value]) ? $statusType[$value] : 'undefined';
    }

    public function getTypeAttribute($value)
    {
        $type = $this->getTypeList();

        return !empty($type[$value]) ? $type[$value] : 'undefined';
    }

    
    /*protected static function newFactory()
    {
        return \Modules\Notification\Database\factories\NotificationFactory::new();
    }*/
    public function sendToUser()
    {
       $model = app($this->send_to_model_type);
       return  $this->hasOne($model::class,'id','send_to');
    }

    public static function createNotification($data)
    {
       $data = [
           'description' =>  $data['description'],
           'send_by' => $data['send_by'],
           'send_to' => $data['user']->id,
           'send_to_model_type' => $data['user']::class,
           'send_by_model_type' => User::class,
           'title' => $data['title']
       ];

       $data = self::create($data);
       
       if( $data->send_to_model_type == Candidate::class) {
          $userData = Candidate::where('id',$data->send_to)->first();
       } else {
          $userData = Employer::where('id',$data->send_to)->first();
       }
       $notification = 
       [
         'notification'=> $data,
         'userData' => $userData
       ];

       SendMail::dispatch($notification);
    }


    
}
