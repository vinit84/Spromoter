<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use JsonException;
use Laravel\Cashier\Cashier;
use Stripe\Exception\ApiErrorException;
use Throwable;

class Plan extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'features' => 'collection',
        'card_features' => 'collection',
        'is_active' => 'boolean',
    ];

    const FEATURES = [
        [
            'title' => 'Collect Reviews',
            'slug' => 'collect-reviews',
            'features' => [
                [
                    'title' => 'Automatic Review Requests',
                    'slug' => 'automatic-review-requests',
                    'type' => 'boolean',
                    'default' => true
                ],
                [
                    'title' => 'Reminder Review Requests',
                    'slug' => 'reminder-review-requests',
                    'type' => 'boolean',
                    'default' => true
                ],
                [
                    'title' => 'Import Existing Reviews',
                    'slug' => 'import-existing-reviews',
                    'type' => 'boolean',
                    'default' => true
                ],
                [
                    'title' => 'Email Templates Library',
                    'slug' => 'email-templates-library',
                    'type' => 'boolean',
                    'default' => true
                ],
                [
                    'title' => 'Manual Review Requests',
                    'slug' => 'manual-review-requests',
                    'type' => 'boolean',
                    'default' => false
                ],
                [
                    'title' => 'Review Request Email Editor',
                    'slug' => 'review-request-email-editor',
                    'type' => 'boolean',
                    'default' => false
                ],
                [
                    'title' => 'Photo & Video Reviews',
                    'slug' => 'photo-video-reviews',
                    'type' => 'boolean',
                    'default' => false
                ],
                [
                    'title' => 'Review Coupons',
                    'slug' => 'review-coupons',
                    'type' => 'boolean',
                    'default' => false
                ],
                [
                    'title' => 'Custom Questions',
                    'slug' => 'custom-questions',
                    'type' => 'boolean',
                    'default' => false
                ]
            ],
        ],
        [
            'title' => 'Display Reviews',
            'slug' => 'display-reviews',
            'features' => [
                [
                    'title' => 'Review Widget',
                    'slug' => 'review-widget',
                    'type' => 'boolean',
                    'default' => true
                ],
                [
                    'title' => 'Star Rating Badge',
                    'slug' => 'star-rating-badge',
                    'type' => 'boolean',
                    'default' => true
                ],
                [
                    'title' => 'Review Sorting',
                    'slug' => 'review-sorting',
                    'type' => 'boolean',
                    'default' => true
                ],
                [
                    'title' => 'Review Tab',
                    'slug' => 'review-tab',
                    'type' => 'boolean',
                    'default' => true
                ],
                [
                    'title' => 'Remove Branding',
                    'slug' => 'remove-branding',
                    'type' => 'boolean',
                    'default' => false
                ]
            ]
        ],
        [
            'title' => 'Manage Reviews',
            'slug' => 'manage-reviews',
            'features' => [
                [
                    'title' => 'Review Moderation',
                    'slug' => 'review-moderation',
                    'type' => 'boolean',
                    'default' => true
                ],
                [
                    'title' => 'Review Commenting',
                    'slug' => 'review-commenting',
                    'type' => 'boolean',
                    'default' => true
                ],
                [
                    'title' => 'Advanced Auto Publishing',
                    'slug' => 'advanced-auto-publishing',
                    'type' => 'boolean',
                    'default' => false
                ]
            ]
        ],
        [
            'title' => 'Analytics',
            'slug' => 'analytics',
            'features' => [
                [
                    'title' => 'Review Dashboard',
                    'slug' => 'review-dashboard',
                    'type' => 'boolean',
                    'default' => true
                ],
                [
                    'title' => 'Email Analytics',
                    'slug' => 'email-analytics',
                    'type' => 'boolean',
                    'default' => true
                ],
                [
                    'title' => 'Review Insights',
                    'slug' => 'review-insights',
                    'type' => 'boolean',
                    'default' => true
                ],
                [
                    'title' => 'Review Reports',
                    'slug' => 'review-reports',
                    'type' => 'boolean',
                    'default' => true
                ]
            ]
        ],
        [
            'title' => 'Priority Support',
            'slug' => 'priority-support',
            'features' => [
                [
                    'title' => 'Email Support',
                    'slug' => 'email-support',
                    'type' => 'boolean',
                    'default' => true
                ],
                [
                    'title' => 'Live Chat Support',
                    'slug' => 'live-chat-support',
                    'type' => 'boolean',
                    'default' => false
                ],
                [
                    'title' => 'Phone Support',
                    'slug' => 'phone-support',
                    'type' => 'boolean',
                    'default' => false
                ]
            ]
        ]
    ];

    public static function getFeatureBySlug($slug)
    {
        foreach (self::FEATURES as $featureGroup) {
            foreach ($featureGroup['features'] as $feature) {
                if ($feature['slug'] === $slug) {
                    return $feature;
                }
            }
        }

        return null;
    }

    public static function getFeatures(): array
    {
        $features = [];

        foreach (self::FEATURES as $featureGroup) {
            foreach ($featureGroup['features'] as $feature) {
                $features[] = $feature;
            }
        }

        return $features;
    }

    public function scopeActive(Builder $query)
    {
        return $query->whereIsActive(true);
    }

    public static function checkStripePlanExists($slug): bool
    {
        try {
            $exists = Cashier::stripe()->products->retrieve($slug);
            if ($exists) {
                return true;
            }
        } catch (Exception $e) {
        }

        return false;
    }

    /**
     * @throws JsonException
     */
    public static function createProduct($title, $description, $slug, $features, $isActive, $mainFeatures = []): \Stripe\Product
    {
        $stripe = Cashier::stripe();

        try {
            return $stripe->products->create([
                'name' => $title,
                'active' => (bool)$isActive,
                'description' => $description,
                'id' => $slug,
                'metadata' => $features,
                'features' => $mainFeatures,
            ]);
        } catch (Exception $e) {
            throw new JsonException(trans('Unable to create product in Stripe'));
        }
    }

    /**
     * @throws ApiErrorException
     * @throws JsonException
     */
    public static function createPrice($product, $amount, $nickName, $interval, $orders, $trialDays)
    {
        $stripe = Cashier::stripe();

        try {
            return $stripe->prices->create([
                'active' => true,
                'billing_scheme' => 'per_unit',
                'currency' => 'usd',
                'nickname' => $nickName,
                'product' => $product->id,
                'unit_amount' => $amount * 100,
                'recurring' => [
                    'interval' => $interval,
                    'trial_period_days' => $trialDays
                ],
            ]);
        } catch (Throwable $e) {
            throw new JsonException(trans('Unable to create price in Stripe'));
        }
    }

    public function updateProduct($title, $description, $features, $isActive): \Stripe\Product
    {
        try {
            $stripe = Cashier::stripe();
            return $stripe->products->update($this->stripe_id, [
                'name' => $title,
                'active' => (bool)$isActive,
                'description' => $description,
                'metadata' => $features
            ]);
        } catch (Exception $e) {
            throw new JsonException(trans('Unable to update product in Stripe'));
        }
    }

    public static function deleteStripePlan($slug): void
    {
        try {
            $stripe = Cashier::stripe();
            $stripe->products->delete($slug);
        } catch (Exception $e) {
        }
    }
}
