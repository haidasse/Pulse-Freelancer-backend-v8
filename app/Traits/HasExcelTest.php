<?php

namespace App\Traits;

use Maatwebsite\Excel\Facades\Excel;

trait HasExcelTest
{
    public function test_user_can_download_export()
    {
        // Excel::fake();

        $response = $this->actingAs($this->user, "api")->get($this->api . 'export');

        $response->assertOk();

        // dd($response);

        $response->assertHeader("content-type", "text/plain");

        // $response->assertSee("Id");
    }
}