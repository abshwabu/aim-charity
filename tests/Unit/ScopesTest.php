<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\DonationMethod;
use App\Models\Faq;
use App\Models\GalleryItem;
use App\Models\ImpactStat;
use App\Models\MemberGroup;
use App\Models\NewsPost;
use App\Models\PageSection;
use App\Models\Partner;
use App\Models\Program;
use App\Models\Step;
use App\Models\TeamMember;
use App\Models\Testimonial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScopesTest extends TestCase
{
    use RefreshDatabase;

    public function test_visible_scope_filters_out_invisible_items(): void
    {
        Program::factory()->create(['title' => 'Visible', 'is_visible' => true]);
        Program::factory()->create(['title' => 'Hidden', 'is_visible' => false]);

        $visiblePrograms = Program::query()->visible()->get();

        $this->assertCount(1, $visiblePrograms);
        $this->assertSame('Visible', $visiblePrograms->first()->title);

        ImpactStat::factory()->create(['label' => 'Visible Stat', 'is_visible' => true]);
        ImpactStat::factory()->create(['label' => 'Hidden Stat', 'is_visible' => false]);

        $visibleStats = ImpactStat::query()->visible()->get();
        $this->assertCount(1, $visibleStats);
        $this->assertSame('Visible Stat', $visibleStats->first()->label);

        Faq::factory()->create(['is_visible' => true]);
        Faq::factory()->create(['is_visible' => false]);
        $this->assertCount(1, Faq::query()->visible()->get());

        NewsPost::factory()->create(['is_visible' => true]);
        NewsPost::factory()->create(['is_visible' => false]);
        $this->assertCount(1, NewsPost::query()->visible()->get());
    }

    public function test_ordered_scope_orders_items_by_sort_order_ascending(): void
    {
        $second = Program::factory()->create(['title' => 'Second', 'sort_order' => 20]);
        $third = Program::factory()->create(['title' => 'Third', 'sort_order' => 30]);
        $first = Program::factory()->create(['title' => 'First', 'sort_order' => 10]);

        $ordered = Program::query()->ordered()->pluck('title')->all();

        $this->assertSame(['First', 'Second', 'Third'], $ordered);
    }

    public function test_all_repeatable_models_support_visible_and_ordered_scopes(): void
    {
        $models = [
            PageSection::class,
            MemberGroup::class,
            Program::class,
            ImpactStat::class,
            Testimonial::class,
            GalleryItem::class,
            Partner::class,
            Faq::class,
            NewsPost::class,
            Step::class,
            DonationMethod::class,
            TeamMember::class,
        ];

        foreach ($models as $modelClass) {
            $modelClass::factory()->create(['sort_order' => 50, 'is_visible' => true]);
            $modelClass::factory()->create(['sort_order' => 10, 'is_visible' => true]);
            $modelClass::factory()->create(['sort_order' => 5, 'is_visible' => false]);

            $results = $modelClass::query()->visible()->ordered()->get();

            $this->assertCount(2, $results, "Failed visible scope on {$modelClass}");
            $this->assertSame(10, $results->first()->sort_order, "Failed ordering on {$modelClass}");
            $this->assertSame(50, $results->last()->sort_order, "Failed ordering on {$modelClass}");
        }
    }
}
