<?php

namespace App\Models;

use App\Traits\CreateOrUpdateUserMember;
use App\Traits\HandleImageDeletions;
use App\Traits\HandleWhatsappPhone;
use App\Traits\HasAgeAttribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use HasFactory, SoftDeletes, CreateOrUpdateUserMember, HandleWhatsappPhone, HandleImageDeletions,HasAgeAttribute;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'dob',
        'gender',
        'family_relation_id',
        'phone1',
        'phone2',
        'whatsapp',
        'image',
        'hasLogin',
        'user_id',
        'member_id',

        'user_email',
        'user_password',
        'is_phone1_whatsapp',
        'is_phone2_whatsapp',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'dob' => 'date',
        'family_relation_id' => 'integer',
        'user_id' => 'integer',
        'member_id' => 'integer',
    ];

    public function familyRelation(): BelongsTo
    {
        return $this->belongsTo(FamilyRelation::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    /**
     * Get the parent member (e.g. the head of the family)
     */
    public function parentMember(): BelongsTo
    {
        return $this->belongsTo(self::class, 'member_id');
    }

    /**
     * Get all the family members (child members) that belong to this member.
     */
    public function familyMembers():HasMany
    {
        return $this->hasMany(self::class, 'member_id');
    }
}
