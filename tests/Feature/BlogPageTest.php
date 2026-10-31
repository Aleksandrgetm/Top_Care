<?php

namespace Tests\Feature;

use Tests\TestCase;

class BlogPageTest extends TestCase
{
    public function test_article_requests_do_not_claim_the_list_as_canonical(): void
    {
        foreach (['?post=example', '?post=example&utm_source=test', '?post=', '?post[]=example'] as $query) {
            $this->get('/blogs'.$query)->assertOk()->assertDontSee('rel="canonical"', false);
        }
    }

    public function test_list_tracking_parameters_do_not_change_its_canonical(): void
    {
        $this->get('/blogs?utm_source=test')->assertOk()
            ->assertSee('href="'.url('/blogs').'"', false);
    }

    public function test_other_pages_keep_their_canonical_even_with_a_post_parameter(): void
    {
        $this->get('/pakalpojumi?post=example')->assertOk()
            ->assertSee('href="'.url('/pakalpojumi').'"', false);
    }

    public function test_blog_has_server_rendered_metadata(): void
    {
        $this->get('/blogs')->assertOk()
            ->assertSee('<title>Blogs | Jaunumi un noderīgi raksti | Top Care Group</title>', false)
            ->assertSee('Padomi, aktualitātes un noderīga informācija par būvniecību, renovāciju un īpašumu uzturēšanu.')
            ->assertSee('href="'.url('/blogs').'"', false)
            ->assertDontSee('https://app.trysoro.com/api/embed/');
    }

    public function test_blog_is_in_the_existing_sitemap(): void
    {
        $this->get('/sitemap.xml')->assertOk()
            ->assertSee('<loc>https://topcare.lv/blogs</loc>', false)
            ->assertSee('<loc>https://topcare.lv/</loc>', false);
    }
}
