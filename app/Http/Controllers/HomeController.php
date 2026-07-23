<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Membership;
use App\Models\MembershipPlan;
use App\Models\Portfolio;
use App\Models\Post;
use App\Models\Product;
use App\Models\Service;
use App\Models\Testimonial;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::query()
            ->with('category')
            ->where('is_active', true)
            ->orderByDesc('is_featured')
            ->latest()
            ->limit(8)
            ->get();

        $featuredServices = Service::query()
            ->where('is_active', true)
            ->orderByDesc('is_featured')
            ->latest()
            ->limit(6)
            ->get();

        $featuredCourses = Course::query()
            ->published()
            ->withCount([
                'modules',
                'lessons as lessons_count' => fn ($query) => $query
                    ->where('course_lessons.is_published', true),
            ])
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->latest()
            ->limit(6)
            ->get();

        $membershipPlans = MembershipPlan::query()
            ->active()
            ->orderByDesc('is_featured')
            ->orderBy('price')
            ->limit(3)
            ->get();

        $portfolios = Portfolio::query()
            ->where('is_active', true)
            ->orderByDesc('is_featured')
            ->latest()
            ->limit(6)
            ->get();

        $testimonials = Testimonial::query()
            ->where('is_active', true)
            ->orderByDesc('is_featured')
            ->latest()
            ->limit(6)
            ->get();

        $latestPosts = Post::query()
            ->where('is_published', true)
            ->where(function ($query) {
                $query
                    ->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->orderByDesc('published_at')
            ->latest()
            ->limit(3)
            ->get();

        $hasActiveMembership = auth()->check()
            && Membership::query()
                ->where('user_id', auth()->id())
                ->active()
                ->exists();

        $statistics = [
            'products' => Product::query()
                ->where('is_active', true)
                ->count(),

            'services' => Service::query()
                ->where('is_active', true)
                ->count(),

            'courses' => Course::query()
                ->published()
                ->count(),

            'members' => Membership::query()
                ->active()
                ->distinct()
                ->count('user_id'),
        ];

        return view('home', compact(
            'featuredProducts',
            'featuredServices',
            'featuredCourses',
            'membershipPlans',
            'portfolios',
            'testimonials',
            'latestPosts',
            'hasActiveMembership',
            'statistics'
        ));
    }
}
