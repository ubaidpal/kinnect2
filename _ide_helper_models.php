<?php
/**
 * An helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App{
/**
 * App\ActivityAction
 *
 * @property integer $action_id
 * @property string $type
 * @property string $subject_type
 * @property integer $subject_id
 * @property string $object_type
 * @property integer $object_id
 * @property string $target_type
 * @property integer $target_id
 * @property string $body
 * @property string $params
 * @property integer $attachment_count
 * @property integer $comment_count
 * @property integer $like_count
 * @property integer $fav_count
 * @property integer $share_count
 * @property integer $dislike_count
 * @property string $is_flagged
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ActivityComment[] $activity_comment
 * @property-read \App\ActivityLike $activity_likes
 * @property-read \App\ActivityFavourite $activity_favourite
 * @property-read \App\ActivityDislike $activity_dislike
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityAction whereActionId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityAction whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityAction whereSubjectType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityAction whereSubjectId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityAction whereObjectType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityAction whereObjectId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityAction whereTargetType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityAction whereTargetId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityAction whereBody($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityAction whereParams($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityAction whereAttachmentCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityAction whereCommentCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityAction whereLikeCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityAction whereFavCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityAction whereShareCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityAction whereDislikeCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityAction whereIsFlagged($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityAction whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityAction whereUpdatedAt($value)
 */
	class ActivityAction extends \Eloquent {}
}

namespace App{
/**
 * App\ActivityComment
 *
 * @property integer $comment_id
 * @property integer $resource_id
 * @property string $poster_type
 * @property integer $poster_id
 * @property string $body
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property integer $like_count
 * @property integer $parent_comment_id
 * @property string $attachment_type
 * @property integer $attachment_id
 * @property string $params
 * @property-read \App\ActivityAction $comment
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityComment whereCommentId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityComment whereResourceId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityComment wherePosterType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityComment wherePosterId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityComment whereBody($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityComment whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityComment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityComment whereLikeCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityComment whereParentCommentId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityComment whereAttachmentType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityComment whereAttachmentId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityComment whereParams($value)
 */
	class ActivityComment extends \Eloquent {}
}

namespace App{
/**
 * App\ActivityDislike
 *
 * @property integer $dislike_id
 * @property integer $poster_id
 * @property string $poster_type
 * @property integer $resource_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\ActivityAction $activity_action
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityDislike whereDislikeId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityDislike wherePosterId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityDislike wherePosterType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityDislike whereResourceId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityDislike whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityDislike whereUpdatedAt($value)
 */
	class ActivityDislike extends \Eloquent {}
}

namespace App{
/**
 * App\ActivityFavourite
 *
 * @property integer $favorite_id
 * @property integer $resource_id
 * @property string $poster_type
 * @property integer $poster_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\ActivityAction $activity_favourite
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityFavourite whereFavoriteId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityFavourite whereResourceId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityFavourite wherePosterType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityFavourite wherePosterId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityFavourite whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityFavourite whereUpdatedAt($value)
 */
	class ActivityFavourite extends \Eloquent {}
}

namespace App{
/**
 * App\ActivityLike
 *
 * @property integer $like_id
 * @property integer $resource_id
 * @property string $poster_type
 * @property integer $poster_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\ActivityAction $activity_action
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityLike whereLikeId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityLike whereResourceId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityLike wherePosterType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityLike wherePosterId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityLike whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityLike whereUpdatedAt($value)
 */
	class ActivityLike extends \Eloquent {}
}

namespace App{
/**
 * App\ActivityNotification
 *
 * @property integer $id
 * @property string $resource_type
 * @property integer $resource_id
 * @property string $subject_type
 * @property integer $subject_id
 * @property string $object_type
 * @property integer $object_id
 * @property string $type
 * @property string $read
 * @property string $clicked
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityNotification whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityNotification whereResourceType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityNotification whereResourceId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityNotification whereSubjectType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityNotification whereSubjectId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityNotification whereObjectType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityNotification whereObjectId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityNotification whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityNotification whereRead($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityNotification whereClicked($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityNotification whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActivityNotification whereUpdatedAt($value)
 */
	class ActivityNotification extends \Eloquent {}
}

namespace App{
/**
 * App\Ad
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property integer $level_id
 * @property float $price
 * @property boolean $sponsored
 * @property boolean $featured
 * @property string $url_option
 * @property boolean $enable
 * @property boolean $network
 * @property boolean $public
 * @property string $price_model
 * @property integer $model_detail
 * @property boolean $renew
 * @property integer $renew_before
 * @property boolean $auto_approve
 * @property integer $order
 * @property string $type
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Ad whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Ad whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Ad whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Ad whereLevelId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Ad wherePrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Ad whereSponsored($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Ad whereFeatured($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Ad whereUrlOption($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Ad whereEnable($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Ad whereNetwork($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Ad wherePublic($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Ad wherePriceModel($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Ad whereModelDetail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Ad whereRenew($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Ad whereRenewBefore($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Ad whereAutoApprove($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Ad whereOrder($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Ad whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Ad whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Ad whereUpdatedAt($value)
 */
	class Ad extends \Eloquent {}
}

namespace App{
/**
 * App\AdCampaign
 *
 * @property integer $id
 * @property string $name
 * @property boolean $status
 * @property integer $owner_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\AdCampaign whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdCampaign whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdCampaign whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdCampaign whereOwnerId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdCampaign whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdCampaign whereUpdatedAt($value)
 */
	class AdCampaign extends \Eloquent {}
}

namespace App{
/**
 * App\AdCancels
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $report_type
 * @property string $report_description
 * @property integer $ad_id
 * @property integer $is_cancel
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\AdCancels whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdCancels whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdCancels whereReportType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdCancels whereReportDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdCancels whereAdId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdCancels whereIsCancel($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdCancels whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdCancels whereUpdatedAt($value)
 */
	class AdCancels extends \Eloquent {}
}

namespace App{
/**
 * App\AdStatistics
 *
 * @property integer $id
 * @property integer $user_ad_id
 * @property integer $ad_campaign_id
 * @property integer $viewer_id
 * @property string $host_name
 * @property string $user_agent
 * @property string $url
 * @property integer $value_click
 * @property integer $value_view
 * @property string $value_like
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\AdStatistics whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdStatistics whereUserAdId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdStatistics whereAdCampaignId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdStatistics whereViewerId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdStatistics whereHostName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdStatistics whereUserAgent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdStatistics whereUrl($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdStatistics whereValueClick($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdStatistics whereValueView($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdStatistics whereValueLike($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdStatistics whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdStatistics whereUpdatedAt($value)
 */
	class AdStatistics extends \Eloquent {}
}

namespace App{
/**
 * App\AdTargets
 *
 * @property integer $id
 * @property integer $user_ad_id
 * @property boolean $birthday_enable
 * @property integer $age_min
 * @property integer $age_max
 * @property boolean $gender
 * @property boolean $profile
 * @property string $country
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\AdTargets whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdTargets whereUserAdId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdTargets whereBirthdayEnable($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdTargets whereAgeMin($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdTargets whereAgeMax($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdTargets whereGender($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdTargets whereProfile($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdTargets whereCountry($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdTargets whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdTargets whereUpdatedAt($value)
 */
	class AdTargets extends \Eloquent {}
}

namespace App{
/**
 * App\AdTargetsCountry
 *
 * @property integer $id
 * @property integer $user_ad_id
 * @property integer $country_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\AdTargetsCountry whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdTargetsCountry whereUserAdId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdTargetsCountry whereCountryId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdTargetsCountry whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdTargetsCountry whereUpdatedAt($value)
 */
	class AdTargetsCountry extends \Eloquent {}
}

namespace App{
/**
 * App\AdTransaction
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $ad_id
 * @property integer $gateway_id
 * @property string $timestamp
 * @property string $type
 * @property string $state
 * @property string $gateway_transaction_id
 * @property string $gateway_parent_transaction_id
 * @property string $gateway_order_id
 * @property float $amount
 * @property string $currency
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\AdTransaction whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdTransaction whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdTransaction whereAdId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdTransaction whereGatewayId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdTransaction whereTimestamp($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdTransaction whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdTransaction whereState($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdTransaction whereGatewayTransactionId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdTransaction whereGatewayParentTransactionId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdTransaction whereGatewayOrderId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdTransaction whereAmount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdTransaction whereCurrency($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdTransaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdTransaction whereUpdatedAt($value)
 */
	class AdTransaction extends \Eloquent {}
}

namespace App{
/**
 * App\AdUserAd
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $ad_type
 * @property integer $package_id
 * @property integer $campaign_id
 * @property string $cads_url
 * @property string $cads_title
 * @property string $cads_body
 * @property integer $owner_id
 * @property integer $photo_id
 * @property string $gateway_order_id
 * @property string $cads_start_date
 * @property string $cads_end_date
 * @property boolean $sponsored
 * @property boolean $featured
 * @property boolean $like
 * @property string $resourece_type
 * @property integer $resourece_id
 * @property boolean $public
 * @property boolean $approved
 * @property boolean $enable
 * @property boolean $status
 * @property string $payment_status
 * @property boolean $declined
 * @property string $approve_date
 * @property string $price_model
 * @property integer $limit_click
 * @property integer $limit_view
 * @property integer $limit_like
 * @property integer $count_view
 * @property integer $count_like
 * @property string $expiry_date
 * @property integer $weight
 * @property float $min_ctr
 * @property integer $gateway_id
 * @property string $gateway_profile_id
 * @property string $renew_by_admin_date
 * @property integer $profile
 * @property integer $story_type
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\AdUserAd whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdUserAd whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdUserAd whereAdType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdUserAd wherePackageId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdUserAd whereCampaignId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdUserAd whereCadsUrl($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdUserAd whereCadsTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdUserAd whereCadsBody($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdUserAd whereOwnerId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdUserAd wherePhotoId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdUserAd whereGatewayOrderId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdUserAd whereCadsStartDate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdUserAd whereCadsEndDate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdUserAd whereSponsored($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdUserAd whereFeatured($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdUserAd whereLike($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdUserAd whereResoureceType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdUserAd whereResoureceId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdUserAd wherePublic($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdUserAd whereApproved($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdUserAd whereEnable($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdUserAd whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdUserAd wherePaymentStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdUserAd whereDeclined($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdUserAd whereApproveDate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdUserAd wherePriceModel($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdUserAd whereLimitClick($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdUserAd whereLimitView($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdUserAd whereLimitLike($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdUserAd whereCountView($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdUserAd whereCountLike($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdUserAd whereExpiryDate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdUserAd whereWeight($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdUserAd whereMinCtr($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdUserAd whereGatewayId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdUserAd whereGatewayProfileId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdUserAd whereRenewByAdminDate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdUserAd whereProfile($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdUserAd whereStoryType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdUserAd whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AdUserAd whereUpdatedAt($value)
 */
	class AdUserAd extends \Eloquent {}
}

namespace App{
/**
 * App\Album
 *
 * @property integer $album_id
 * @property string $title
 * @property string $description
 * @property string $owner_type
 * @property integer $owner_id
 * @property integer $category_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_date
 * @property integer $photo_id
 * @property integer $view_count
 * @property integer $comment_count
 * @property boolean $search
 * @property string $type
 * @property integer $he_featured
 * @property-read \App\User $User
 * @property-read \App\AlbumCategory $AlbumCategory
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\AlbumPhoto[] $AlbumPhotos
 * @property-read \App\StorageFile $cover_photo
 * @method static \Illuminate\Database\Query\Builder|\App\Album whereAlbumId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Album whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Album whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Album whereOwnerType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Album whereOwnerId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Album whereCategoryId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Album whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Album whereUpdatedDate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Album wherePhotoId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Album whereViewCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Album whereCommentCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Album whereSearch($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Album whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Album whereHeFeatured($value)
 */
	class Album extends \Eloquent {}
}

namespace App{
/**
 * App\AlbumCategory
 *
 * @property integer $category_id
 * @property integer $user_id
 * @property string $category_name
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Album[] $Albums
 * @method static \Illuminate\Database\Query\Builder|\App\AlbumCategory whereCategoryId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AlbumCategory whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AlbumCategory whereCategoryName($value)
 */
	class AlbumCategory extends \Eloquent {}
}

namespace App{
/**
 * App\AlbumPhoto
 *
 * @property integer $photo_id
 * @property integer $album_id
 * @property string $title
 * @property integer $parent_id
 * @property string $description
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property integer $order
 * @property string $owner_type
 * @property integer $owner_id
 * @property integer $file_id
 * @property integer $view_count
 * @property integer $comment_count
 * @property integer $he_featured
 * @property integer $share_count
 * @property-read \App\User $User
 * @property-read \App\Album $Albums
 * @property-read \App\StorageFile $storage_file
 * @method static \Illuminate\Database\Query\Builder|\App\AlbumPhoto wherePhotoId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AlbumPhoto whereAlbumId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AlbumPhoto whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AlbumPhoto whereParentId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AlbumPhoto whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AlbumPhoto whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AlbumPhoto whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AlbumPhoto whereOrder($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AlbumPhoto whereOwnerType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AlbumPhoto whereOwnerId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AlbumPhoto whereFileId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AlbumPhoto whereViewCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AlbumPhoto whereCommentCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AlbumPhoto whereHeFeatured($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AlbumPhoto whereShareCount($value)
 */
	class AlbumPhoto extends \Eloquent {}
}

namespace App{
/**
 * App\AuthorizationAllow
 *
 * @property integer $id
 * @property string $resource_type
 * @property integer $resource_id
 * @property string $action
 * @property integer $permission
 * @property string $params
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\AuthorizationAllow whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AuthorizationAllow whereResourceType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AuthorizationAllow whereResourceId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AuthorizationAllow whereAction($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AuthorizationAllow wherePermission($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AuthorizationAllow whereParams($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AuthorizationAllow whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AuthorizationAllow whereUpdatedAt($value)
 */
	class AuthorizationAllow extends \Eloquent {}
}

namespace App{
/**
 * App\Battle
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $title
 * @property string $description
 * @property string $starttime
 * @property string $endtime
 * @property integer $view_count
 * @property integer $comment_count
 * @property boolean $search
 * @property boolean $is_closed
 * @property integer $vote_count
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\User $User
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\BattleOption[] $BattleOption
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\BattleVote[] $BattleVote
 * @property-read mixed $check_closed
 * @method static \Illuminate\Database\Query\Builder|\App\Battle whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Battle whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Battle whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Battle whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Battle whereStarttime($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Battle whereEndtime($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Battle whereViewCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Battle whereCommentCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Battle whereSearch($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Battle whereIsClosed($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Battle whereVoteCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Battle whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Battle whereUpdatedAt($value)
 */
	class Battle extends \Eloquent {}
}

namespace App{
/**
 * App\BattleOption
 *
 * @property integer $id
 * @property integer $battle_id
 * @property integer $brand_id
 * @property integer $votes
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Battle $Battle
 * @property-read \App\Brand $Brand
 * @property-read \App\User $brand_detail
 * @method static \Illuminate\Database\Query\Builder|\App\BattleOption whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\BattleOption whereBattleId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\BattleOption whereBrandId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\BattleOption whereVotes($value)
 * @method static \Illuminate\Database\Query\Builder|\App\BattleOption whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\BattleOption whereUpdatedAt($value)
 */
	class BattleOption extends \Eloquent {}
}

namespace App{
/**
 * App\BattleVote
 *
 * @property integer $id
 * @property integer $battle_id
 * @property integer $user_id
 * @property integer $battle_option_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\User $User
 * @property-read \App\Battle $Battle
 * @method static \Illuminate\Database\Query\Builder|\App\BattleVote whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\BattleVote whereBattleId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\BattleVote whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\BattleVote whereBattleOptionId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\BattleVote whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\BattleVote whereUpdatedAt($value)
 */
	class BattleVote extends \Eloquent {}
}

namespace App{
/**
 * App\Brand
 *
 * @property integer $id
 * @property string $brand_history
 * @property string $brand_name
 * @property string $description
 * @property string $store_created
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\BattleOption[] $BattleOption
 * @property-read \App\User $brand_detail
 * @method static \Illuminate\Database\Query\Builder|\App\Brand whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Brand whereBrandHistory($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Brand whereBrandName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Brand whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Brand whereStoreCreated($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Brand whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Brand whereUpdatedAt($value)
 */
	class Brand extends \Eloquent {}
}

namespace App{
/**
 * App\BrandMembership
 *
 * @property integer $id
 * @property integer $brand_id This is a id of users table. For user who's type is Brand.
 * @property integer $user_id
 * @property boolean $brand_approved
 * @property boolean $user_approved
 * @property boolean $active
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\BrandMembership whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\BrandMembership whereBrandId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\BrandMembership whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\BrandMembership whereBrandApproved($value)
 * @method static \Illuminate\Database\Query\Builder|\App\BrandMembership whereUserApproved($value)
 * @method static \Illuminate\Database\Query\Builder|\App\BrandMembership whereActive($value)
 * @method static \Illuminate\Database\Query\Builder|\App\BrandMembership whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\BrandMembership whereUpdatedAt($value)
 */
	class BrandMembership extends \Eloquent {}
}

namespace App{
/**
 * App\Consumer
 *
 * @property integer $id
 * @property string $gender
 * @property string $birthdate
 * @property string $about_me
 * @property string $personnel_info
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\User $user
 * @property-read \App\User $user_detail
 * @method static \Illuminate\Database\Query\Builder|\App\Consumer whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Consumer whereGender($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Consumer whereBirthdate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Consumer whereAboutMe($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Consumer wherePersonnelInfo($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Consumer whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Consumer whereUpdatedAt($value)
 */
	class Consumer extends \Eloquent {}
}

namespace App{
/**
 * App\Conversation
 *
 * @property integer $id
 * @property string $deleted_at
 * @property string $type
 * @property string $title
 * @property integer $file_id
 * @property integer $updated_by
 * @property \Carbon\Carbon $created_at
 * @property integer $created_by
 * @property string $conv_for
 * @property boolean $status 1:Open, 0:Closed
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Conversation whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Conversation whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Conversation whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Conversation whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Conversation whereFileId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Conversation whereUpdatedBy($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Conversation whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Conversation whereCreatedBy($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Conversation whereConvFor($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Conversation whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Conversation whereUpdatedAt($value)
 */
	class Conversation extends \Eloquent {}
}

namespace App{
/**
 * App\ConversationUser
 *
 * @property integer $conv_id
 * @property integer $user_id
 * @method static \Illuminate\Database\Query\Builder|\App\ConversationUser whereConvId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ConversationUser whereUserId($value)
 */
	class ConversationUser extends \Eloquent {}
}

namespace App{
/**
 * App\Country
 *
 * @property integer $id
 * @property string $iso
 * @property string $name
 * @property string $region
 * @property string $nicename
 * @property string $iso3
 * @property integer $numcode
 * @property integer $phonecode
 * @method static \Illuminate\Database\Query\Builder|\App\Country whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Country whereIso($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Country whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Country whereRegion($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Country whereNicename($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Country whereIso3($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Country whereNumcode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Country wherePhonecode($value)
 */
	class Country extends \Eloquent {}
}

namespace App{
/**
 * App\Event
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property integer $user_id
 * @property string $parent_type
 * @property integer $parent_id
 * @property string $starttime
 * @property string $endtime
 * @property string $host
 * @property string $location
 * @property integer $view_count
 * @property integer $member_count
 * @property integer $approval_required
 * @property integer $member_can_invite
 * @property integer $photo_id
 * @property integer $category_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Album $Album
 * @method static \Illuminate\Database\Query\Builder|\App\Event whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Event whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Event whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Event whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Event whereParentType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Event whereParentId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Event whereStarttime($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Event whereEndtime($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Event whereHost($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Event whereLocation($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Event whereViewCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Event whereMemberCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Event whereApprovalRequired($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Event whereMemberCanInvite($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Event wherePhotoId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Event whereCategoryId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Event whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Event whereUpdatedAt($value)
 */
	class Event extends \Eloquent {}
}

namespace App{
/**
 * App\EventMembership
 *
 * @property integer $id
 * @property integer $event_id
 * @property integer $user_id
 * @property boolean $active
 * @property boolean $event_approved
 * @property boolean $user_approved
 * @property string $message
 * @property boolean $rsvp
 * @property string $title
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\EventMembership whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\EventMembership whereEventId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\EventMembership whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\EventMembership whereActive($value)
 * @method static \Illuminate\Database\Query\Builder|\App\EventMembership whereEventApproved($value)
 * @method static \Illuminate\Database\Query\Builder|\App\EventMembership whereUserApproved($value)
 * @method static \Illuminate\Database\Query\Builder|\App\EventMembership whereMessage($value)
 * @method static \Illuminate\Database\Query\Builder|\App\EventMembership whereRsvp($value)
 * @method static \Illuminate\Database\Query\Builder|\App\EventMembership whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\EventMembership whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\EventMembership whereUpdatedAt($value)
 */
	class EventMembership extends \Eloquent {}
}

namespace App{
/**
 * App\Feedback
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $feedback
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Feedback whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Feedback whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Feedback whereFeedback($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Feedback whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Feedback whereUpdatedAt($value)
 */
	class Feedback extends \Eloquent {}
}

namespace App{
/**
 * App\Friendship
 *
 * @property integer $id
 * @property integer $resource_id
 * @property integer $user_id
 * @property boolean $active
 * @property boolean $resource_approved
 * @property boolean $user_approved
 * @property string $message
 * @property string $is_viewed
 * @property string $description
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\User $resource
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Query\Builder|\App\Friendship whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Friendship whereResourceId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Friendship whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Friendship whereActive($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Friendship whereResourceApproved($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Friendship whereUserApproved($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Friendship whereMessage($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Friendship whereIsViewed($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Friendship whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Friendship whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Friendship whereUpdatedAt($value)
 */
	class Friendship extends \Eloquent {}
}

namespace App{
/**
 * App\Group
 *
 * @property integer $id
 * @property integer $creator_id
 * @property string $title
 * @property string $description
 * @property integer $category_id
 * @property boolean $search
 * @property boolean $members_can_invite
 * @property boolean $approval_required
 * @property integer $photo_id
 * @property integer $cover_photo_id
 * @property integer $member_count
 * @property integer $view_count
 * @property integer $is_moderator
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Group whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Group whereCreatorId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Group whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Group whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Group whereCategoryId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Group whereSearch($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Group whereMembersCanInvite($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Group whereApprovalRequired($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Group wherePhotoId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Group whereCoverPhotoId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Group whereMemberCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Group whereViewCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Group whereIsModerator($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Group whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Group whereUpdatedAt($value)
 */
	class Group extends \Eloquent {}
}

namespace App{
/**
 * App\GroupMembership
 *
 * @property integer $group_id
 * @property integer $user_id
 * @property boolean $active
 * @property boolean $group_owner_approved
 * @property boolean $user_approved
 * @property string $message
 * @property string $title
 * @property integer $is_moderator
 * @property integer $user_approved_moderator
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\GroupMembership whereGroupId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\GroupMembership whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\GroupMembership whereActive($value)
 * @method static \Illuminate\Database\Query\Builder|\App\GroupMembership whereGroupOwnerApproved($value)
 * @method static \Illuminate\Database\Query\Builder|\App\GroupMembership whereUserApproved($value)
 * @method static \Illuminate\Database\Query\Builder|\App\GroupMembership whereMessage($value)
 * @method static \Illuminate\Database\Query\Builder|\App\GroupMembership whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\GroupMembership whereIsModerator($value)
 * @method static \Illuminate\Database\Query\Builder|\App\GroupMembership whereUserApprovedModerator($value)
 * @method static \Illuminate\Database\Query\Builder|\App\GroupMembership whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\GroupMembership whereUpdatedAt($value)
 */
	class GroupMembership extends \Eloquent {}
}

namespace App{
/**
 * App\GroupMessage
 *
 * @property integer $id
 * @property integer $chat_id
 * @property integer $sender_id
 * @property string $sender_type
 * @property integer $receiver_id
 * @property string $receiver_type
 * @property string $chat_message
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\GroupMessage whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\GroupMessage whereChatId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\GroupMessage whereSenderId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\GroupMessage whereSenderType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\GroupMessage whereReceiverId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\GroupMessage whereReceiverType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\GroupMessage whereChatMessage($value)
 * @method static \Illuminate\Database\Query\Builder|\App\GroupMessage whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\GroupMessage whereUpdatedAt($value)
 */
	class GroupMessage extends \Eloquent {}
}

namespace App{
/**
 * App\Hashtag
 *
 */
	class Hashtag extends \Eloquent {}
}

namespace App{
/**
 * App\Invitation
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $receiver_id
 * @property string $status 0:No Action, 1:Received,2:Ignored
 * @property integer $object_id
 * @property string $object_type
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Invitation whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Invitation whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Invitation whereReceiverId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Invitation whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Invitation whereObjectId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Invitation whereObjectType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Invitation whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Invitation whereUpdatedAt($value)
 */
	class Invitation extends \Eloquent {}
}

namespace App{
/**
 * App\Like
 *
 * @property integer $like_id
 * @property string $resource_type
 * @property integer $resource_id
 * @property string $poster_type
 * @property integer $poster_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Like whereLikeId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Like whereResourceType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Like whereResourceId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Like wherePosterType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Like wherePosterId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Like whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Like whereUpdatedAt($value)
 */
	class Like extends \Eloquent {}
}

namespace App{
/**
 * App\Link
 *
 * @property integer $link_id
 * @property string $uri
 * @property string $title
 * @property string $description
 * @property integer $photo_id
 * @property string $parent_type
 * @property integer $parent_id
 * @property string $owner_type
 * @property integer $owner_id
 * @property integer $view_count
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property boolean $search
 * @method static \Illuminate\Database\Query\Builder|\App\Link whereLinkId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Link whereUri($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Link whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Link whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Link wherePhotoId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Link whereParentType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Link whereParentId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Link whereOwnerType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Link whereOwnerId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Link whereViewCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Link whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Link whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Link whereSearch($value)
 */
	class Link extends \Eloquent {}
}

namespace App{
/**
 * App\Message
 *
 * @property integer $id
 * @property integer $sender_id
 * @property integer $conv_id
 * @property string $content
 * @property integer $file_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Message whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Message whereSenderId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Message whereConvId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Message whereContent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Message whereFileId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Message whereUpdatedAt($value)
 */
	class Message extends \Eloquent {}
}

namespace App{
/**
 * App\MessageStatus
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $msg_id
 * @property boolean $self
 * @property integer $status
 * @method static \Illuminate\Database\Query\Builder|\App\MessageStatus whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\MessageStatus whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\MessageStatus whereMsgId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\MessageStatus whereSelf($value)
 * @method static \Illuminate\Database\Query\Builder|\App\MessageStatus whereStatus($value)
 */
	class MessageStatus extends \Eloquent {}
}

namespace App{
/**
 * App\Poll
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $title
 * @property string $description
 * @property string $starttime
 * @property string $endtime
 * @property integer $view_count
 * @property integer $comment_count
 * @property boolean $search
 * @property boolean $is_closed
 * @property integer $vote_count
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\User $User
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\PollOption[] $PollOption
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\PollVote[] $PollVote
 * @property-read mixed $check_closed
 * @method static \Illuminate\Database\Query\Builder|\App\Poll whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Poll whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Poll whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Poll whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Poll whereStarttime($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Poll whereEndtime($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Poll whereViewCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Poll whereCommentCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Poll whereSearch($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Poll whereIsClosed($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Poll whereVoteCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Poll whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Poll whereUpdatedAt($value)
 */
	class Poll extends \Eloquent {}
}

namespace App{
/**
 * App\PollOption
 *
 * @property integer $id
 * @property integer $poll_id
 * @property string $poll_option
 * @property integer $votes
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Poll $Poll
 * @method static \Illuminate\Database\Query\Builder|\App\PollOption whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PollOption wherePollId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PollOption wherePollOption($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PollOption whereVotes($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PollOption whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PollOption whereUpdatedAt($value)
 */
	class PollOption extends \Eloquent {}
}

namespace App{
/**
 * App\PollVote
 *
 * @property integer $id
 * @property integer $poll_id
 * @property integer $user_id
 * @property integer $poll_option_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\User $User
 * @property-read \App\Poll $Poll
 * @method static \Illuminate\Database\Query\Builder|\App\PollVote whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PollVote wherePollId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PollVote whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PollVote wherePollOptionId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PollVote whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PollVote whereUpdatedAt($value)
 */
	class PollVote extends \Eloquent {}
}

namespace App{
/**
 * App\Report
 *
 * @property integer $report_id
 * @property integer $user_id
 * @property string $category
 * @property string $description
 * @property integer $action_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property boolean $read
 * @property-read \App\ActivityAction $post
 * @method static \Illuminate\Database\Query\Builder|\App\Report whereReportId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Report whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Report whereCategory($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Report whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Report whereActionId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Report whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Report whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Report whereRead($value)
 */
	class Report extends \Eloquent {}
}

namespace App{
/**
 * App\SnsEndpoint
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $arn Amazon Key Against Device to push a notification
 * @property string $platform
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\User $User
 * @property-read mixed $check_closed
 * @method static \Illuminate\Database\Query\Builder|\App\SnsEndpoint whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\SnsEndpoint whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\SnsEndpoint whereArn($value)
 * @method static \Illuminate\Database\Query\Builder|\App\SnsEndpoint wherePlatform($value)
 * @method static \Illuminate\Database\Query\Builder|\App\SnsEndpoint whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\SnsEndpoint whereUpdatedAt($value)
 */
	class SnsEndpoint extends \Eloquent {}
}

namespace App{
/**
 * App\StorageFile
 *
 * @property integer $file_id
 * @property integer $parent_file_id
 * @property string $type
 * @property string $parent_type
 * @property integer $parent_id
 * @property integer $user_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $storage_path
 * @property string $extension
 * @property string $name
 * @property string $mime_type
 * @property integer $size
 * @property string $hash
 * @property boolean $is_temp
 * @property integer $share_count
 * @property-read \App\AlbumPhoto $album_photo
 * @property-read \App\Album $album_cover_photo
 * @method static \Illuminate\Database\Query\Builder|\App\StorageFile whereFileId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StorageFile whereParentFileId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StorageFile whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StorageFile whereParentType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StorageFile whereParentId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StorageFile whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StorageFile whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StorageFile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StorageFile whereStoragePath($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StorageFile whereExtension($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StorageFile whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StorageFile whereMimeType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StorageFile whereSize($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StorageFile whereHash($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StorageFile whereIsTemp($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StorageFile whereShareCount($value)
 */
	class StorageFile extends \Eloquent {}
}

namespace App{
/**
 * App\StoreClaimRequest
 *
 * @property integer $id
 * @property integer $seller_id
 * @property string $owner_type
 * @property integer $owner_id
 * @property string $status
 * @property float $amount
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\User $seller
 * @property-read \kinnect2Store\Store\StoreClaim $store_claim
 * @method static \Illuminate\Database\Query\Builder|\App\StoreClaimRequest whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StoreClaimRequest whereSellerId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StoreClaimRequest whereOwnerType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StoreClaimRequest whereOwnerId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StoreClaimRequest whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StoreClaimRequest whereAmount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StoreClaimRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\StoreClaimRequest whereUpdatedAt($value)
 */
	class StoreClaimRequest extends \Eloquent {}
}

namespace App{
/**
 * App\Timezone
 *
 * @property integer $time_zone_id
 * @property string $country
 * @property string $value
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Timezone whereTimeZoneId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Timezone whereCountry($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Timezone whereValue($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Timezone whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Timezone whereUpdatedAt($value)
 */
	class Timezone extends \Eloquent {}
}

namespace App{
/**
 * App\User
 *
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $first_name
 * @property string $last_name
 * @property string $password
 * @property string $activation_code
 * @property boolean $active
 * @property boolean $resent
 * @property string $username
 * @property string $displayname
 * @property integer $userable_id
 * @property string $userable_type
 * @property integer $country
 * @property string $contact_info
 * @property string $website
 * @property string $facebook
 * @property string $twitter
 * @property integer $photo_id
 * @property integer $cover_photo_id
 * @property string $mood
 * @property string $mode_date
 * @property string $salt
 * @property string $locale
 * @property string $language
 * @property string $timezone
 * @property boolean $search
 * @property boolean $show_profileviewers
 * @property string $user_type
 * @property integer $invites_used
 * @property integer $extra_used
 * @property boolean $enabled
 * @property boolean $verified
 * @property boolean $approved
 * @property string $creation_ip
 * @property string $lastlogin_date
 * @property string $lastlogin_ip
 * @property integer $skore
 * @property integer $member_count
 * @property integer $view_count
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted
 * @property string $token_expiry_date
 * @property integer $login_counter
 * @property string $store_created
 * @property \Carbon\Carbon $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $userable
 * @property-read \App\Consumer $consumer
 * @property-read \App\Brand $brand
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Poll[] $Poll
 * @property-read \App\PollVote $PollVote
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Battle[] $Battle
 * @property-read \App\BattleVote $BattleVote
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\GroupMembership[] $GroupMembership
 * @property-read \App\Friendship $resource
 * @property-read \App\Friendship $user_detail
 * @property-read \App\Consumer $consumer_detail
 * @property-read \App\Brand $brand_detail
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ActivityNotification[] $notifications
 * @property-read \App\ActivityAction $object_detail
 * @property-read \App\StorageFile $storage_file
 * @property-read \App\AlbumPhoto $album_photo
 * @property-read \Illuminate\Database\Eloquent\Collection|\Bican\Roles\Models\Role[] $roles
 * @property-read \Illuminate\Database\Eloquent\Collection|\Bican\Roles\Models\Permission[] $userPermissions
 * @method static \Illuminate\Database\Query\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereFirstName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereLastName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereActivationCode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereActive($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereResent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereUsername($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereDisplayname($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereUserableId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereUserableType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereCountry($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereContactInfo($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereWebsite($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereFacebook($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereTwitter($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User wherePhotoId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereCoverPhotoId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereMood($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereModeDate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereSalt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereLocale($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereLanguage($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereTimezone($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereSearch($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereShowProfileviewers($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereUserType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereInvitesUsed($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereExtraUsed($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereEnabled($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereVerified($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereApproved($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereCreationIp($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereLastloginDate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereLastloginIp($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereSkore($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereMemberCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereViewCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereDeleted($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereTokenExpiryDate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereLoginCounter($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereStoreCreated($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereDeletedAt($value)
 */
	class User extends \Eloquent {}
}

namespace App{
/**
 * App\UserBrand
 *
 * @property integer $id
 * @property string $brand_history
 * @property string $brand_name
 * @property string $description
 * @property string $store_created
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\UserBrand whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\UserBrand whereBrandHistory($value)
 * @method static \Illuminate\Database\Query\Builder|\App\UserBrand whereBrandName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\UserBrand whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\UserBrand whereStoreCreated($value)
 * @method static \Illuminate\Database\Query\Builder|\App\UserBrand whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\UserBrand whereUpdatedAt($value)
 */
	class UserBrand extends \Eloquent {}
}

namespace App{
/**
 * App\UserMembership
 *
 * @property integer $id
 * @property integer $resource_id
 * @property integer $user_id
 * @property boolean $active
 * @property boolean $resource_approved
 * @property boolean $user_approved
 * @property string $message
 * @property string $is_viewed
 * @property string $description
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\UserMembership whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\UserMembership whereResourceId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\UserMembership whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\UserMembership whereActive($value)
 * @method static \Illuminate\Database\Query\Builder|\App\UserMembership whereResourceApproved($value)
 * @method static \Illuminate\Database\Query\Builder|\App\UserMembership whereUserApproved($value)
 * @method static \Illuminate\Database\Query\Builder|\App\UserMembership whereMessage($value)
 * @method static \Illuminate\Database\Query\Builder|\App\UserMembership whereIsViewed($value)
 * @method static \Illuminate\Database\Query\Builder|\App\UserMembership whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\UserMembership whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\UserMembership whereUpdatedAt($value)
 */
	class UserMembership extends \Eloquent {}
}

namespace App{
/**
 * App\Usersetting
 *
 * @property integer $setting_id
 * @property string $category
 * @property string $setting
 * @property boolean $setting_value
 * @property integer $user_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Usersetting whereSettingId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Usersetting whereCategory($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Usersetting whereSetting($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Usersetting whereSettingValue($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Usersetting whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Usersetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Usersetting whereUpdatedAt($value)
 */
	class Usersetting extends \Eloquent {}
}

namespace App{
/**
 * App\users_membership
 *
 */
	class users_membership extends \Eloquent {}
}

namespace App{
/**
 * App\Video
 *
 * @property integer $video_id
 * @property string $title
 * @property string $description
 * @property boolean $search
 * @property string $owner_type
 * @property integer $owner_id
 * @property string $parent_type
 * @property integer $parent_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property integer $view_count
 * @property integer $comment_count
 * @property boolean $type
 * @property string $code
 * @property integer $photo_id
 * @property float $rating
 * @property integer $category_id
 * @property boolean $status
 * @property integer $file_id
 * @property integer $duration
 * @property integer $rotation
 * @method static \Illuminate\Database\Query\Builder|\App\Video whereVideoId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Video whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Video whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Video whereSearch($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Video whereOwnerType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Video whereOwnerId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Video whereParentType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Video whereParentId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Video whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Video whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Video whereViewCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Video whereCommentCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Video whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Video whereCode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Video wherePhotoId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Video whereRating($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Video whereCategoryId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Video whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Video whereFileId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Video whereDuration($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Video whereRotation($value)
 */
	class Video extends \Eloquent {}
}

