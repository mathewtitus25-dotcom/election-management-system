<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BLO newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BLO newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BLO query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BLO whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BLO whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BLO whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BLO whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BLO whereUserId($value)
 */
	class BLO extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string $manifesto
 * @property string $status
 * @property int $votes_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $dob
 * @property string|null $gender
 * @property string|null $mobile
 * @property string|null $voter_id
 * @property string|null $aadhaar
 * @property string|null $address
 * @property string|null $candidate_id
 * @property string|null $email
 * @property string|null $qualification
 * @property string|null $photo
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Candidate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Candidate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Candidate query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Candidate whereAadhaar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Candidate whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Candidate whereCandidateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Candidate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Candidate whereDob($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Candidate whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Candidate whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Candidate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Candidate whereManifesto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Candidate whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Candidate wherePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Candidate whereQualification($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Candidate whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Candidate whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Candidate whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Candidate whereVoterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Candidate whereVotesCount($value)
 */
	class Candidate extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $start_date
 * @property \Illuminate\Support\Carbon|null $end_date
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ElectionConfig newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ElectionConfig newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ElectionConfig query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ElectionConfig whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ElectionConfig whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ElectionConfig whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ElectionConfig whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ElectionConfig whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ElectionConfig whereUpdatedAt($value)
 */
	class ElectionConfig extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property bool $is_result_published
 * @property bool $was_published
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Candidate> $candidates
 * @property-read int|null $candidates_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Voter> $voters
 * @property-read int|null $voters_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Panchayat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Panchayat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Panchayat query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Panchayat whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Panchayat whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Panchayat whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Panchayat whereIsResultPublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Panchayat whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Panchayat whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Panchayat whereWasPublished($value)
 */
	class Panchayat extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $role
 * @property int|null $panchayat_id
 * @property string|null $otp
 * @property \Illuminate\Support\Carbon|null $otp_expires_at
 * @property bool $is_verified
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\BLO|null $blo
 * @property-read \App\Models\Candidate|null $candidate
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\Panchayat|null $panchayat
 * @property-read \App\Models\Voter|null $voter
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereOtp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereOtpExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePanchayatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $candidate_id
 * @property int $panchayat_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Candidate $candidate
 * @property-read \App\Models\Panchayat $panchayat
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vote query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vote whereCandidateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vote whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vote whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vote wherePanchayatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vote whereUpdatedAt($value)
 */
	class Vote extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string $voter_id_number
 * @property string $dob
 * @property string $status
 * @property bool $has_voted
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $captured_photo
 * @property string|null $aadhaar_number
 * @property string|null $mobile
 * @property-read mixed $panchayat
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voter query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voter whereAadhaarNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voter whereCapturedPhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voter whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voter whereDob($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voter whereHasVoted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voter whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voter whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voter whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voter whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voter whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voter whereVoterIdNumber($value)
 */
	class Voter extends \Eloquent {}
}

