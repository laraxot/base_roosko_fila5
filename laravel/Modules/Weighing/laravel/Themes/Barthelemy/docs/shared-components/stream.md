---
title: "stream 1"
type: note
tags: [documentation]
created: 2026-09-26
updated: 2026-09-26
qmd: "stream 1"
issues: []
discussions: []
status: draft
---

https://laravel-news.com/temporary-directory

-----------------------------------------------

use Illuminate\Support\Facades\Http;
use Spatie\TemporaryDirectory\TemporaryDirectory;

// Normalize the video and get the filename
$videoUrl = str($videoUrl)->replace(' ', '%20');
$tmpFile = $videoUrl->afterLast('/');

// Create a temporary directory and download a file to that path
$tmpDir = TemporaryDirectory::make();
$tmpPath = $tmpDir->path($tmpFile);
Http::sink($tmpPath)->throw()->get($videoUrl->toString());

// Process the file

// Cleanup the temporary file
$tmpFile->delete();

----------------------------------------------------------------------------
