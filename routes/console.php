<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('perf:analyze', function () {
    $routes = [
        'Home' => '/',
        'Course List' => '/khoa-hoc',
        'Search Suggestions' => '/search/suggestions?q=laravel',
        'Cart' => '/cart',
    ];

    $course = \App\Models\Course::where('approval_status', 'published')->where('status', 1)->first();
    if ($course) {
        $routes['Course Detail'] = "/chi-tiet/{$course->course_name_slug}";
    } else {
        $this->warn('No published course found for Course Detail testing.');
    }

    $this->info("=== Starting Performance Analysis ===");

    $activeUri = null;
    $queriesByUri = [];

    \Illuminate\Support\Facades\DB::listen(function ($query) use (&$activeUri, &$queriesByUri) {
        if ($activeUri) {
            $queriesByUri[$activeUri][] = [
                'sql' => $query->sql,
                'bindings' => $query->bindings,
                'time' => $query->time,
            ];
        }
    });

    foreach ($routes as $name => $uri) {
        $this->info("\n----------------------------------");
        $this->info("Testing: {$name} ({$uri})");
        $this->info("----------------------------------");

        $activeUri = $uri;
        $queriesByUri[$uri] = [];

        // Setup request
        $request = \Illuminate\Http\Request::create($uri, 'GET');
        
        // Handle request
        $startTime = microtime(true);
        $response = app()->handle($request);
        $endTime = microtime(true);

        $durationMs = ($endTime - $startTime) * 1000;
        $queries = $queriesByUri[$uri];
        $totalQueries = count($queries);

        $this->line("Status Code: " . $response->getStatusCode());
        $this->line("Total Request Time: " . round($durationMs, 2) . " ms");
        $this->line("Total DB Queries: " . $totalQueries);

        // Analyze duplicate queries
        $sqlCounts = [];
        $queryDetails = [];
        foreach ($queries as $q) {
            $sql = $q['sql'];
            $sqlCounts[$sql] = ($sqlCounts[$sql] ?? 0) + 1;
            $queryDetails[$sql][] = $q;
        }

        // Show duplicates
        $duplicates = array_filter($sqlCounts, function ($count) {
            return $count > 1;
        });

        if (count($duplicates) > 0) {
            $this->error("Found " . count($duplicates) . " duplicate query patterns (potential N+1):");
            $printed = 0;
            foreach ($duplicates as $sql => $count) {
                if ($printed >= 10) {
                    $this->line("  ... and " . (count($duplicates) - 10) . " more duplicate query patterns.");
                    break;
                }
                $this->warn("  - Count: {$count} times");
                $this->line("    SQL: " . substr($sql, 0, 150) . (strlen($sql) > 150 ? '...' : ''));
                $sample = $queryDetails[$sql][0];
                $this->line("    Sample Bindings: " . json_encode($sample['bindings']));
                $printed++;
            }
        } else {
            $this->info("No duplicate queries detected.");
        }

        // List top 3 slowest queries
        usort($queries, function ($a, $b) {
            return $b['time'] <=> $a['time'];
        });

        $this->line("\nTop 3 Slowest Queries:");
        for ($i = 0; $i < min(3, count($queries)); $i++) {
            $q = $queries[$i];
            $this->line("  " . ($i + 1) . ". [" . round($q['time'], 2) . " ms] " . substr($q['sql'], 0, 120) . (strlen($q['sql']) > 120 ? '...' : ''));
        }
    }

    $activeUri = null;
});

